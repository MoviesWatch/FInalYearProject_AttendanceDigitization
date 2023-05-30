<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get('/faculty/api/odDetail/$odID', function ($odID) {
        FacultyAuth::verify(2);
        echo json_encode(OdDetail::read(["odDetails.odID" => $odID]));
    });

}
registerRoutes();
