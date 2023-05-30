<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get('/faculty/api/timetable/c/$classID', function ($classID) {
        FacultyAuth::verify(1);
        echo json_encode(Timetable::read(["classID" => $classID]));
    });

    get('/faculty/api/timetable/c_d/$classSubjectID/$day', function (
        $classSubjectID,
        $day
    ) {
        FacultyAuth::verify(1);
        echo json_encode(
            Timetable::read([
            "timetable.classSubjectID" => $classSubjectID,
            "day" => $day,
      ])
        );
    });

    get('/faculty/api/timetable/f/$facultyID', function ($facultyID) {
        FacultyAuth::verify(1);
        echo json_encode(
            Timetable::readWithFacultyID([
            "facultyID" => $facultyID,
      ])
        );
    });

    post("/faculty/api/timetable", function () {
        FacultyAuth::verify(2);
        $period = json_decode(file_get_contents("php://input"), true);
        Timetable::commonCreate($period);
        echo SuccessMessage::$add;
    });

    post("/faculty/api/timetable/addMore", function () {
        FacultyAuth::verify(2);
        Timetable::delete([
          "classesAndSubjects.classID" => $_SESSION["classID"],
        ]);
        $periods = json_decode(file_get_contents("php://input"), true);
        Timetable::commonCreateMore($periods);
        echo SuccessMessage::$add;
    });

    delete("/faculty/api/timetable", function () {
        FacultyAuth::verify(2);
        Timetable::delete([
        "classesAndSubjects.classID" => $_SESSION["classID"],
        ]);
        echo SuccessMessage::$delete;
    });

    delete('/faculty/api/timetable/$day/$hour', function ($day, $hour) {
        FacultyAuth::verify(2);
        Timetable::delete([
        "classesAndSubjects.classID" => $_SESSION["classID"],
        "day" => $day,
        "hour" => $hour,
        ]);
        echo SuccessMessage::$delete;
    });
}
registerRoutes();
