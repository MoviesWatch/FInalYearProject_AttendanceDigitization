<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
session_start();
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    post("/academicSemester/api/admin/login", function () {
        $admin = json_decode(file_get_contents("php://input"));
        echo json_encode(AdminAuth::login($admin));
    });

    get("/academicSemester/api/admin/logout", function () {
        AdminAuth::verify();
        echo json_encode(AdminAuth::logout());
    });

    put("/academicSemester/api/admin/changePassword", function () {
        AdminAuth::verify();
        $newPassword = json_decode(file_get_contents("php://input"), true)[0];
        echo json_encode(Admin::update($newPassword, $_SESSION["username"]));
    });
}
registerRoutes();
