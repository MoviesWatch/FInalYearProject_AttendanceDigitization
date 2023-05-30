<?php

class StudentAuth
{
  public static function verify()
  {
    if (!isset($_SESSION["regNo"])) {
      return CustomException::notLoggedIn();
      exit();
    }
  }

  static function login($student)
  {
    $_SESSION["db"] = $student->academicSemester;
    $studentFromDB = StudentCredential::read(
      ["students.regNo" => $student->regNo],
      ["name", "password", "classID", "inactive"]
    );

    if (empty($studentFromDB) || $studentFromDB[0]->inactive) {
      //invalid regNo
      return CustomException::invalidUsername();
      exit();
    }

    $genuine = password_verify($student->password, $studentFromDB[0]->password);

    if ($genuine) {
      session_regenerate_id();
      $_SESSION["regNo"] = $student->regNo;
      $_SESSION["classID"] = $studentFromDB[0]->classID;
      return [
        "regNo" => $student->regNo,
        "name" => $studentFromDB[0]->name,
        "classID" => $studentFromDB[0]->classID,
      ];
    } else {
      //invalid dateOfBirth
      return CustomException::invalidPassword();
      exit();
    }
  }

  static function logout()
  {
    session_destroy();
    return true;
  }
}
