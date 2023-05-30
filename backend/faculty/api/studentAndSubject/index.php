<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get('/faculty/api/studentAndSubject/cs/$classSubjectID', function (
        $classSubjectID
    ) {
        FacultyAuth::verify(1);
        echo json_encode(
            StudentAndSubject::read([
            "studentsAndSubjects.classSubjectID" => $classSubjectID,
      ], ["students.regNo","students.name",])
        );
    });

    get('/faculty/api/studentAndSubject/c/$classID', function ($classID) {
        FacultyAuth::verify(1);
        echo json_encode(StudentAndSubject::read(["students.classID" => $classID]));
    });

    get('/faculty/api/studentAndSubject/r/$regNo', function ($regNo) {
        FacultyAuth::verify(1);
        echo json_encode(StudentAndSubject::read(["students.regNo" => $regNo]));
    });

    get('/faculty/api/studentAndSubject/d_s/$departmentCode/$semester', function ($departmentCode, $semester) {
        FacultyAuth::verify(1);
        echo json_encode(StudentAndSubject::read(["classes.departmentCode" => $departmentCode,"classes.semester"=>$semester]));
    });

    post("/faculty/api/studentAndSubject/addMore", function () {
        FacultyAuth::verify(2);
        $studentAndSubjects = json_decode(file_get_contents("php://input"), true);
        StudentAndSubject::commonCreateMore($studentAndSubjects);
        echo SuccessMessage::$add;
    });

    delete(
        '/faculty/api/studentAndSubject/$regNo/$classSubjectID',
        function ($regNo, $classSubjectID) {
            FacultyAuth::verify(2);
            StudentAndSubject::commonDelete([
            "regNo" => $regNo,
            "classSubjectID" => $classSubjectID,
            ]);
            echo SuccessMessage::$delete;
        }
    );
}
registerRoutes();
