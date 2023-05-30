<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get('/faculty/api/workingDay/$classID', function ($classID) {
        FacultyAuth::verify(1);
        echo json_encode(WorkingDay::commonRead(["classID" => $classID]));
    });

    get('/faculty/api/workingDay/d_s/$departmentCode/$semester', function ($departmentCode, $semester) {
        FacultyAuth::verify(1);
        echo json_encode(WorkingDay::read(["classes.departmentCode" => $departmentCode,"classes.semester"=>$semester]));
    });

    post("/faculty/api/workingDay", function () {
        FacultyAuth::verify(2);
        $workingDay = json_decode(file_get_contents("php://input"), true);
        WorkingDay::commonCreate($workingDay);
        echo SuccessMessage::$add;
    });

    post("/faculty/api/workingDay/addMore", function () {
        FacultyAuth::verify(2);
        $workingDays = json_decode(file_get_contents("php://input"), true);
        WorkingDay::commonCreateMore($workingDays);
        echo SuccessMessage::$add;
    });

    delete('/faculty/api/workingDay/$classID/$date', function ($classID, $date) {
        FacultyAuth::verify(2);
        WorkingDay::commonDelete(["classID" => $classID, "date" => $date]);
        echo SuccessMessage::$delete;
    });
}
registerRoutes();
