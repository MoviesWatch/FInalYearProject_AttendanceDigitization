<?php

class Timetable extends Table
{
    protected static $tableName = "timetable";

    public static function read($filters = [])
    {
        $query = "SELECT day, hour, periodID, subjectAcronym, classesAndSubjects.classSubjectID,classesAndSubjects.classID
    FROM timetable
    INNER JOIN classesAndSubjects
    ON classesAndSubjects.classSubjectID = timetable.classSubjectID
    INNER JOIN subjects
    ON classesAndSubjects.subjectCode = subjects.subjectCode";
        return self::commonRead($filters, [], $query);
    }
    public static function readWithFacultyID($filters = [])
    {
        $query = "SELECT day, hour, periodID, subjectAcronym, classesAndSubjects.classSubjectID, departmentCode, semester
    FROM facultiesAndSubjects
    INNER JOIN classesAndSubjects
    ON facultiesAndSubjects.classSubjectID = classesAndSubjects.classSubjectID
    INNER JOIN timetable
    ON classesAndSubjects.classSubjectID = timetable.classSubjectID
    INNER JOIN classes
    ON classes.classID = classesAndSubjects.classID
    INNER JOIN subjects
    ON classesAndSubjects.subjectCode = subjects.subjectCode";
        return self::commonRead($filters, [], $query);
    }

    public static function delete($filters = [])
    {
        $query = "DELETE timetable FROM timetable 
        INNER JOIN classesAndSubjects 
        ON classesAndSubjects.classSubjectID = timetable.classSubjectID";
        return self::commonDelete($filters, $query);
    }
}
