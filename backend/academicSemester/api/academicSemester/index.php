<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
session_start();
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get("/academicSemester/api/academicSemester", function () {
        $_SESSION["db"] = Database::$masterDB;
        echo json_encode(AcademicSemester::commonRead());
    });

    post("/academicSemester/api/academicSemester", function () {
        AdminAuth::verify();
        $semester = json_decode(file_get_contents("php://input"), true);
        $name =
          $semester["startYear"] .
          "-" .
          $semester["endYear"] .
          "-" .
          ($semester["semesterType"] == 0 ? "Odd" : "Even");
        $databasename = $name;
        AcademicSemesterDB::create($databasename);
        new Connection($_SESSION["db"]);
        AcademicSemester::commonCreate([
          "startYear" => $semester["startYear"],
          "endYear" => $semester["endYear"],
          "semesterType" => intval($semester["semesterType"]),
          "name" => $name,
          "databaseName" => $databasename,
        ]);
        echo "Created successfully";
    });

    put('/academicSemester/api/academicSemester/$semesterID', function (
        $semesterID
    ) {
        $update = json_decode(file_get_contents("php://input"), true);
        echo json_encode(
            AcademicSemester::commonUpdate(["id" => $semesterID], $update)
        );
    });

    delete(
        '/academicSemester/api/academicSemester/$semesterID/$databaseName',
        function ($semesterID, $databaseName) {
            AcademicSemesterDB::delete($databaseName);
            AcademicSemester::commonDelete([
              "id" => $semesterID,
            ]);
            echo "Deleted successfully";
        }
    );
}
registerRoutes();
