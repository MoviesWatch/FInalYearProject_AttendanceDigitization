<?php
class ActivePeriod extends Table
{
  protected static $tableName = "activePeriods";
  public static function read($filters = [])
  {
    $query = "SELECT DISTINCT date, day, hour, timetable.classSubjectID as classSubjectID,classesAndSubjects.subjectCode as subjectCode, subjects.subjectAcronym as subjectAcronym,timetable.periodID as periodID, classesAndSubjects.classID as classID FROM activePeriods
    INNER JOIN timetable 
    ON timetable.periodID = activePeriods.periodID
    INNER JOIN classesAndSubjects
    ON classesAndSubjects.classSubjectID = timetable.classSubjectID
    INNER JOIN studentsAndSubjects
    ON studentsAndSubjects.classSubjectID = timetable.classSubjectID
    INNER JOIN subjects
    ON subjects.subjectCode=classesAndSubjects.subjectCode";
    return self::commonRead($filters, [], $query);
  }
}
