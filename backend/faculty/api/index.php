<?php

header("Content-Type: application/json");
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";
error_reporting(0);
session_start();
function registerRoutes()
{
    get("/faculty/api/academicSemester", function () {
        $_SESSION["db"] = Database::$masterDB;
        echo json_encode(AcademicSemester::commonRead(["fActive" => 1]));
    });

    post("/faculty/api/login", function () {
        $faculty = json_decode(file_get_contents("php://input"));
        echo json_encode(FacultyAuth::login($faculty));
    });

    get("/faculty/api/logout", function () {
        FacultyAuth::verify(1);
        FacultyAuth::logout();
    });

    put("/faculty/api/profile", function () {
        FacultyAuth::verify(1);
        $update = json_decode(file_get_contents("php://input"), true);
        FacultyAuth::updateProfile($update);
        echo SuccessMessage::$update;
    });

    put("/faculty/api/changePassword", function () {
        FacultyAuth::verify(1);
        $newPassword = json_decode(file_get_contents("php://input"), true)[0];
        FacultyCredential::update($newPassword, $_SESSION["facultyID"]);
        SuccessMessage::$update;
    });
}
registerRoutes();
