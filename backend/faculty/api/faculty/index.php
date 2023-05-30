<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get("/faculty/api/faculty/all", function () {
        FacultyAuth::verify(1);
        echo json_encode(Faculty::read());
    });

    get("/faculty/api/faculty", function () {
        FacultyAuth::verify(1);
        echo json_encode(Faculty::read([], true));
    });

    post("/faculty/api/faculty", function () {
        FacultyAuth::verify(5);
        $faculty = json_decode(file_get_contents("php://input"), true);
        Faculty::create($faculty);
        echo SuccessMessage::$add;
    });

    put('/faculty/api/faculty/$facultyID', function ($facultyID) {
        FacultyAuth::verify(5);
        $update = json_decode(file_get_contents("php://input"), true);
        Faculty::commonUpdate(["facultyID" => $facultyID], $update);
        echo SuccessMessage::$update;
    });

    delete('/faculty/api/faculty/$facultyID', function ($facultyID) {
        FacultyAuth::verify(5);
        Faculty::commonUpdate(
            ["facultyID" => $facultyID],
            ["inactive" => date("Y-m-d")]
        );
        echo SuccessMessage::$update;
    });

    put('/faculty/api/faculty/retrive/$facultyID', function ($facultyID) {
        FacultyAuth::verify(5);
        Faculty::commonUpdate(["facultyID" => $facultyID], ["inactive" => null]);
        echo SuccessMessage::$update;
    });

    put('/faculty/api/faculty/changePassword/$facultyID', function ($facultyID) {
        FacultyAuth::verify(5);
        $newPassword = json_decode(file_get_contents("php://input"), true)[0];
        echo json_encode(FacultyCredential::update($newPassword, $facultyID));
        echo SuccessMessage::$update;
    });

    delete('/faculty/api/faculty/permanentDelete/$facultyID', function (
        $facultyID
    ) {
        FacultyAuth::verify(5);
        Faculty::commonDelete(["facultyID" => $facultyID]);
        echo SuccessMessage::$delete;
    });
}
registerRoutes();
