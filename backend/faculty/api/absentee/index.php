<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
session_start();
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";
FacultyAuth::verify(1);

function registerRoutes()
{
    post("/faculty/api/absentee/addNew", function () {
        $absenteesAndActivePeriods = json_decode(
            file_get_contents("php://input"),
            true
        );
        $absentees = $absenteesAndActivePeriods["absentees"];
        $activePeriods = $absenteesAndActivePeriods["activePeriods"];
        ActivePeriod::commonCreateMore($activePeriods);
        Absentee::createMore($absentees);
        echo SuccessMessage::$add;
    });

    post("/faculty/api/absentee", function () {
        $absentees = json_decode(file_get_contents("php://input"), true);
        Absentee::createMore($absentees);
        echo SuccessMessage::$add;
    });

    delete('/faculty/api/absentee/$date/$periodID/$regNo', function (
        $date,
        $periodID,
        $regNo
    ) {
        Absentee::commonDelete([
          "regNo" => $regNo,
          "periodID" => $periodID,
          "regNo" => $regNo,
        ]);
        echo SuccessMessage::$update;
    });
}
registerRoutes();
