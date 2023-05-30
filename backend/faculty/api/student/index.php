<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get("/faculty/api/student", function () {
        FacultyAuth::verify(1);
        echo json_encode(Student::read());
    });

    get('/faculty/api/student/r/$regNo', function ($regNo) {
        FacultyAuth::verify(1);
        echo json_encode(Student::read(["regNo" => $regNo]));
    });

    get('/faculty/api/student/c/$classID', function ($classID) {
        FacultyAuth::verify(1);
        echo json_encode(Student::read(["students.classID" => $classID]));
    });

    get('/faculty/api/student/d/$departmentCode', function ($departmentCode) {
        FacultyAuth::verify(1);
        echo json_encode(
            Student::read(["classes.departmentCode" => $departmentCode])
        );
    });

    get('/faculty/api/student/d_s/$departmentCode/$semester', function (
        $departmentCode,
        $semester
    ) {
        FacultyAuth::verify(1);
        echo json_encode(
            Student::read([
            "classes.departmentCode" => $departmentCode,
            "semester" => $semester,
      ])
        );
    });

    post("/faculty/api/student", function () {
        FacultyAuth::verify(2);
        $student = json_decode(file_get_contents("php://input"), true);
        Student::create($student);
        echo SuccessMessage::$add;
    });

    post("/faculty/api/student/addMore", function () {
        FacultyAuth::verify(2);
        $students = json_decode(file_get_contents("php://input"), true);
        Student::createMore($students);
        echo SuccessMessage::$add;
    });

    put('/faculty/api/student/$regNo', function ($regNo) {
        FacultyAuth::verify(2);
        $update = json_decode(file_get_contents("php://input"), true);
        Student::commonUpdate(["regNo" => $regNo], $update);
        echo SuccessMessage::$update;
    });

    put('/faculty/api/student/changePassword/$regNo', function ($regNo) {
        FacultyAuth::verify(2);
        $newPassword = json_decode(file_get_contents("php://input"), true)[0];
        StudentCredential::update($newPassword, $regNo);
        echo SuccessMessage::$update;
    });

    delete('/faculty/api/student/$regNo', function ($regNo) {
        FacultyAuth::verify(2);
        Student::commonUpdate(["regNo" => $regNo], ["inactive" => date("Y-m-d")]);
        echo SuccessMessage::$update;
    });

    delete('/faculty/api/student/permanentDelete/$regNo', function ($regNo) {
        FacultyAuth::verify(2);
        Student::commonDelete(["regNo" => $regNo]);
        echo SuccessMessage::$delete;
    });
}
registerRoutes();
