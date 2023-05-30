<?php

class FacultyAuth
{
    public static function login($faculty)
    {
        $_SESSION["db"] = $faculty->academicSemester;
        $facultyFromDB = FacultyCredential::read([
          "faculties.facultyID" => $faculty->facultyID,
        ]);
        if (empty($facultyFromDB)) {
            echo json_encode(CustomException::invalidUsername());
            exit();
        }

        if ($faculty->accessID > $facultyFromDB[0]->accessID) {
            echo json_encode(CustomException::invalidAccess());
            exit();
        }
        $genuine = password_verify($faculty->password, $facultyFromDB[0]->password);

        if ($genuine) {
            session_regenerate_id();
            $_SESSION["facultyID"] = $faculty->facultyID;
            $_SESSION["accessID"] = $faculty->accessID;
            $_SESSION["classID"] = $facultyFromDB[0]->classID;
            $_SESSION["departmentCode"] = $facultyFromDB[0]->departmentCode;
            return [
              "facultyID" => $faculty->facultyID,
              "accessID" => $faculty->accessID,
              "name" => $facultyFromDB[0]->name,
              "departmentCode" => $facultyFromDB[0]->name,
              "classID" => $facultyFromDB[0]->classID,
            ];
        } else {
            echo json_encode(CustomException::invalidPassword());
            exit();
        }
    }

    public static function verify($accessID = 1)
    {
        if (!isset($_SESSION["facultyID"])) {
            echo json_encode(CustomException::notLoggedIn());
            exit();
        }
        if ($accessID > $_SESSION["accessID"]) {
            echo json_encode(CustomException::notAllowed());
            exit();
        }
    }

    public static function logout()
    {
        session_destroy();
        return true;
        exit();
    }

    public static function updateProfile($update)
    {
        self::verify();
        return Faculty::commonUpdate(
            ["facultyID" => $_SESSION["facultyID"]],
            $update
        );
    }
}
