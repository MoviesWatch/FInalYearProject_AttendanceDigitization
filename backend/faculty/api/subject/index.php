<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get("/faculty/api/subject", function () {
        FacultyAuth::verify(1);
        echo json_encode(Subject::commonRead());
    });

    post("/faculty/api/subject", function () {
        FacultyAuth::verify(5);
        $subject = json_decode(file_get_contents("php://input"), true);
        Subject::commonCreate($subject);
        echo SuccessMessage::$add;
    });

    post("/faculty/api/subject/addMore", function () {
        FacultyAuth::verify(5);
        $subjects = json_decode(file_get_contents("php://input"), true);
        Subject::commonCreateMore($subjects);
        echo SuccessMessage::$add;
    });

    put('/faculty/api/subject/$subjectCode', function ($subjectCode) {
        FacultyAuth::verify(5);
        $update = json_decode(file_get_contents("php://input"), true);
        Student::commonUpdate(["subjectCode" => $subjectCode], $update);
        echo SuccessMessage::$update;
    });

    delete('/faculty/api/subject/$subjectCode', function ($subjectCode) {
        FacultyAuth::verify(5);
        Subject::commonDelete(["subjectCode" => $subjectCode]);
        echo SuccessMessage::$delete;
    });
}
registerRoutes();
