<?php

class FacultyCredential extends Table
{
    protected static $tableName = "facultyCredentials";
    public static function read($filters = [], $columns = [])
    {
        $query =
          "SELECT " .
          (count($columns) > 0 ? join(", ", $columns) : " * ") .
          " FROM facultyCredentials 
      INNER JOIN faculties 
      ON faculties.facultyID = facultyCredentials.facultyID";
        return self::commonRead($filters, [], $query);
    }

    public static function create($facultyID, $password)
    {
        $password = password_hash($facultyID, PASSWORD_BCRYPT, [
          "cost" => 10,
        ]);
        return self::commonCreate([
          "facultyID" => $facultyID,
          "password" => $password,
        ]);
    }

    public static function update($newPassword, $facultyID)
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
                "facultyID" => $facultyID,
        ],
                ["password" => $newPassword]
            );
        }
        $_SESSION["db"] = $temp;
        new Connection($temp);
    }
}
