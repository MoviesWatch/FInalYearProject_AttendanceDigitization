<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get("/faculty/api/subjectType", function () {
        FacultyAuth::verify(1);
        echo json_encode(SubjectType::commonRead());
    });
}
registerRoutes();
