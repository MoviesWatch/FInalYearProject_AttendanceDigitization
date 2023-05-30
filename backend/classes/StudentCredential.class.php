<?php
class StudentCredential extends Table
{
  protected static $tableName = "studentCredentials";
  public static function read($filters = [], $columns = [])
  {
    $query =
      "SELECT " .
      (count($columns) > 0 ? join(", ", $columns) : " * ") .
      " FROM studentCredentials 
      INNER JOIN students 
      ON students.regNo = studentCredentials.regNo";
    return self::commonRead($filters, [], $query);
  }

  public static function create($regNo, $password)
  {
    $password = password_hash($password, PASSWORD_BCRYPT, [
      "cost" => 10,
    ]);
    return self::commonCreate(["regNo" => $regNo, "password" => $password]);
  }
  public static function update($newPassword, $regNo)
  {
    $newPassword = password_hash($newPassword, PASSWORD_BCRYPT, [
      "cost" => 10,
    ]);
    $temp = $_SESSION["db"];
    $_SESSION["db"] = "academicSemesters";
    $academicSemesters = AcademicSemester::commonRead();
    foreach ($academicSemesters as $academicSemester) {
      new Connection($academicSemester->databaseName);
      self::commonUpdate(
        [
          "regNo" => $regNo,
        ],
        ["password" => $newPassword]
      );
    }
    $_SESSION["db"] = $temp;
    new Connection($temp);
    return "Changed successfully";
  }
}
