<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get("/faculty/api/facultyAndSubject", function () {
        FacultyAuth::verify(1);
        echo json_encode(FacultyAndSubject::read());
    });

    get('/faculty/api/facultyAndSubject/$facultyID', function ($facultyID) {
        FacultyAuth::verify(1);
        echo json_encode(
            FacultyAndSubject::read(["faculties.facultyID" => $facultyID])
        );
    });

    post("/faculty/api/facultyAndSubject", function () {
        FacultyAuth::verify(2);
        $facultyAndSubject = json_decode(file_get_contents("php://input"), true);
        FacultyAndSubject::commonCreate($facultyAndSubject);
        echo SuccessMessage::$add;
    });

    post("/faculty/api/facultyAndSubject/addMore", function () {
        FacultyAuth::verify(2);
        $facultiesAndSubjects = json_decode(file_get_contents("php://input"), true);
        FacultyAndSubject::commonCreateMore($facultiesAndSubjects);
        echo SuccessMessage::$add;
    });

    put('/faculty/api/facultyAndSubject/$facultyID/$classSubjectID', function (
        $facultyID,
        $classSubjectID
    ) {
        FacultyAuth::verify(2);
        $update = json_decode(file_get_contents("php://input"), true);
        FacultyAndSubject::commonUpdate(
            ["facultyID" => $facultyID, "classSubjectID" => $classSubjectID],
            $update
        );
        echo SuccessMessage::$update;
    });

    delete('/faculty/api/facultyAndSubject/$facultyID/$classSubjectID', function (
        $classSubjectID,
        $facultyID
    ) {
        FacultyAuth::verify(2);
        FacultyAndSubject::commonDelete([
        "facultyID" => $facultyID,
        "classSubjectID" => $classSubjectID,
        ]);
        echo SuccessMessage::$delete;
    });
}
registerRoutes();
