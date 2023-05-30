<?php
class ClassAndSubject extends Table
{
  protected static $tableName = "classesAndSubjects";
  //   protected static $className = "ClassAndSubject";
  public static function read($filters = [])
  {
    $query = "SELECT * FROM classesAndSubjects
    INNER JOIN classes
    ON classesAndSubjects.classID = classes.classID
    INNER JOIN subjects
    ON classesAndSubjects.subjectCode = subjects.subjectCode
    INNER JOIN subjectTypes
    ON classesAndSubjects.typeID = subjectTypes.typeID
    ";
    return self::commonRead($filters, [], $query);
  }
}
