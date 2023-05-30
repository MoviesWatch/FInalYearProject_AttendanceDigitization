<?php

class Student extends Table
{
  protected static $tableName = "students";
  public static function read($filters = [], $columns = [])
  {
    $query = "SELECT * FROM students
    INNER JOIN classes
    ON students.classID = classes.classID";
    return self::commonRead($filters, $columns, $query);
  }

  public static function create($student)
  {
    self::commonCreate($student);
    StudentCredential::create($student["regNo"], $student["dateOfBirth"]);
    return "Added successfully";
  }

  public static function createMore($students)
  {
    foreach ($students as $student) {
      self::create($student);
    }
    return "Added successfully";
  }
}
