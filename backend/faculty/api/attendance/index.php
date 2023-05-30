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
  get("/faculty/api/attendance/c", function () {
    echo json_encode(Attendance::readWithClassID($_SESSION["classID"]));
  });

  get('/faculty/api/attendance/cs/$classSubjectID', function ($classSubjectID) {
    echo json_encode(Attendance::readWithClassSubjectID($classSubjectID));
  });

  get('/faculty/api/attendance/r/$regNo', function ($regNo) {
    echo json_encode(Attendance::readWithRegNo($regNo));
  });
}
registerRoutes();
