<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
session_start();
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get("/student/api/academicSemester", function () {
        $_SESSION["db"] = Database::$masterDB;
        echo json_encode(AcademicSemester::commonRead(["sActive" => 1]));
    });

    post("/student/api/login", function () {
        $student = json_decode(file_get_contents("php://input"));
        echo json_encode(StudentAuth::login($student));
    });

    get("/student/api/logout", function () {
        StudentAuth::verify();
        StudentAuth::logout();
    });

    get("/student/api/timetable", function () {
        StudentAuth::verify();
        echo json_encode(
            Timetable::read([
            "classesAndSubjects.classID" => $_SESSION["classID"],
      ])
        );
    });

    get("/student/api/profile", function () {
        StudentAuth::verify();
        echo json_encode(Student::read(["regNo" => $_SESSION["regNo"]])[0]);
    });

    get("/student/api/attendance", function () {
        StudentAuth::verify();
        echo json_encode(
            Attendance::readWithRegNo($_SESSION["regNo"], $_SESSION["classID"])
        );
    });

    put("/student/api/changePassword", function () {
        StudentAuth::verify();
        $newPassword = json_decode(file_get_contents("php://input"), true)[0];
        StudentCredential::update($newPassword, $_SESSION["regNo"]);
        echo SuccessMessage::$update;
    });
}

registerRoutes();
