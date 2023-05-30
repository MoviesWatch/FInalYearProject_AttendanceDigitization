<?php
class WorkingPeriod extends Table
{
  protected static $tableName = "";
  // public static function read($filters = [])
  // {
  //   $query = "SELECT DISTINCT date,timetableDerived.day,hour,periodID,classSubjectID,workingdays.classID,subjectCode, subjectAcronym
  //   FROM workingDays
  //   CROSS JOIN ( SELECT day, hour, periodID, timetable.classSubjectID, classesAndSubjects.classID, classesAndSubjects.subjectCode ,subjects.subjectAcronym
  //                FROM timetable
  //                INNER JOIN classesAndSubjects
  //                ON classesAndSubjects.classSubjectID = timetable.classSubjectID
  //                INNER JOIN subjects
  //                ON classesAndSubjects.subjectCode = subjects.subjectCode
  //              ) as timetableDerived
  //   ON timetableDerived.day = workingdays.day AND timetableDerived.classID = workingdays.classID";
  //   return self::commonRead($filters, [], $query);
  // }
  public static function read($filters = [])
  {
    $query = "SELECT DISTINCT date, day, hour, timetable.classSubjectID FROM activePeriods
    INNER JOIN timetable 
    ON timetable.periodID = activePeriods.periodID
    INNER JOIN classesAndSubjects
    ON classesAndSubjects.classSubjectID = timetable.classSubjectID
    INNER JOIN studentsAndSubjects
    ON studentsAndSubjects.classSubjectID = timetable.classSubjectID";
    return self::commonRead($filters, [], $query);
  }

  // public static function readForRegNo($regNo)
  // {
  //   $query = "SELECT DISTINCT date,timetableDerived.day,hour,periodID,classSubjectID,workingdays.classID FROM workingDays
  //   CROSS JOIN ( SELECT day, hour, periodID, timetable.classSubjectID, classesAndSubjects.classID FROM timetable INNER JOIN classesAndSubjects ON classesAndSubjects.classSubjectID = timetable.classSubjectID) as timetableDerived
  //   ON timetableDerived.day = workingdays.day AND timetableDerived.classID = workingdays.classID
  //   WHERE workingDays.classID = (SELECT classID FROM students WHERE regNo = ?);";
  //   return Connection::makeQuery("WorkingPeriod", $query, [$regNo]);
  // }
  // public static function ra($regNo){
  //   $query = "SELECT * FROM activePeriods
  //   INNER JOIN timetable
  //   ON timetable.periodID = activePeriod.PerioID
  //   INNER JOIN studentsAndSubjects"
  // }
}
