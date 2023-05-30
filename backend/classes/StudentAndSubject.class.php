<?php

class StudentAndSubject extends Table
{
    protected static $tableName = "studentsAndSubjects";
    public static function read($filters = [])
    {
        $query = "SELECT * FROM studentsAndSubjects 
      INNER JOIN students 
      ON studentsAndSubjects.regNo = students.regNo
      INNER JOIN classesAndSubjects
      ON classesAndSubjects.classSubjectID = studentsAndSubjects.classSubjectID
      INNER JOIN subjects
      ON subjects.subjectCode = classesAndSubjects.subjectCode
      INNER JOIN classes
      ON classes.classID = students.classID";
        return self::commonRead($filters, [], $query);
    }
}
