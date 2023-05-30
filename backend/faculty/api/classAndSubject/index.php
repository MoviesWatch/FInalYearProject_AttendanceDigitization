<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get("/faculty/api/classAndSubject", function () {
        FacultyAuth::verify(1);
        echo json_encode(ClassAndSubject::read());
    });

    get('/faculty/api/classAndSubject/$classID', function ($classID) {
        FacultyAuth::verify(1);
        echo json_encode(ClassAndSubject::read(["classes.classID" => $classID]));
    });

    get('/faculty/api/classAndSubject/d_s/$departmentCode/$semester', function ($departmentCode, $semester) {
        FacultyAuth::verify(1);
        echo json_encode(ClassAndSubject::read(["classes.departmentCode" => $departmentCode,"classes.semester"=>$semester]));
    });

    post("/faculty/api/classAndSubject", function () {
        FacultyAuth::verify(2);
        $classAndSubject = json_decode(file_get_contents("php://input"), true);
        ClassAndSubject::commonCreate($classAndSubject);
        echo SuccessMessage::$add;
    });

    post("/faculty/api/classAndSubject/addMore", function () {
        FacultyAuth::verify(2);
        $classesAndSubjects = json_decode(file_get_contents("php://input"), true);
        ClassAndSubject::commonCreateMore($classesAndSubjects);
        echo SuccessMessage::$add;
    });

    delete('/faculty/api/classAndSubject/$classSubjectID', function (
        $classSubjectID
    ) {
        FacultyAuth::verify(2);
        ClassAndSubject::commonDelete(["classSubjectID" => $classSubjectID]);
        echo SuccessMessage::$delete;
    });
}
registerRoutes();
