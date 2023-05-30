<?php

class Absentee extends Table
{
    protected static $tableName = "absentees";
    public static function read($filters)
    {
        $query = "SELECT date,day,hour,regNo,timetable.periodID,timetable.classSubjectID,classes.classID,subjects.subjectCode,subjects.subjectAcronym FROM absentees 
              INNER JOIN timetable 
              ON timetable.periodID = absentees.periodID
              INNER JOIN classesAndSubjects
              ON classesAndSubjects.classSubjectID = timetable.classSubjectID
              INNER JOIN subjects
              ON classesAndSubjects.subjectCode = subjects.subjectCode
              INNER JOIN classes
              ON classes.classID = classesAndSubjects.classID";
        return self::commonRead($filters, [], $query);
    }

    public static function createMore($absentees)
    {
        foreach ($absentees as $absentee) {
            $ods = OdDetail::read([
              "statusID" => 2,
              "date" => $absentee["date"],
              "odDetails.regNo" => $absentee["regNo"],
              "odDetails.hour" => $absentee["hour"],
            ]);
            if (count($ods) > 0) {
                continue;
            }
            self::commonCreate([
              "date" => $absentee["date"],
              "periodID" => $absentee["periodID"],
              "regNo" => $absentee["regNo"],
            ]);
        }
    }
}
