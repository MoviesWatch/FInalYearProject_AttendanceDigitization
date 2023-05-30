<?php
class FacultyAndSubject extends Table
{
  protected static $tableName = "facultiesAndSubjects";
  public static function read($filters = [])
  {
    $query = "SELECT classesAndSubjects.classSubjectID,classes.classID,classes.departmentCode,faculties.facultyID,faculties.name,classes.semester,subjects.subject,subjects.subjectAcronym,subjects.subjectCode
    FROM facultiesAndSubjects 
    INNER JOIN faculties 
    ON faculties.facultyID = facultiesAndSubjects.facultyID
    INNER JOIN classesAndSubjects
    ON classesAndSubjects.classSubjectID = facultiesAndSubjects.classSubjectID
    INNER JOIN classes
    ON classes.classID = classesAndSubjects.classID
    INNER JOIN subjects
    ON subjects.subjectCode = classesAndSubjects.subjectCode
    ";
    return self::commonRead($filters, [], $query);
  }
}
