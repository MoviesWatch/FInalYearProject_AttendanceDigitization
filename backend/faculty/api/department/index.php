<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get("/faculty/api/department", function () {
        FacultyAuth::verify(1);
        echo json_encode(Department::commonRead());
    });

    post("/faculty/api/department", function () {
        FacultyAuth::verify(5);
        $department = json_decode(file_get_contents("php://input"), true);
        Department::commonCreate($department);
        _Class::createMore($department['departmentCode']);
        echo SuccessMessage::$add;
    });

    put('/faculty/api/department/$departmentCode', function ($departmentCode) {
        FacultyAuth::verify(5);
        $update = json_decode(file_get_contents("php://input"), true);
        Department::commonUpdate(["departmentCode" => $departmentCode], $update);
        echo SuccessMessage::$update;
    });

    delete('/faculty/api/department/$departmentCode', function ($departmentCode) {
        FacultyAuth::verify(5);
        Department::commonDelete(["departmentCode" => $departmentCode]);
        echo SuccessMessage::$delete;

    });
}
registerRoutes();
