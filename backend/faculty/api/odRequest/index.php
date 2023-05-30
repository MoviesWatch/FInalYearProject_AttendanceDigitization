<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/includes/autoloader.php";
require $_SERVER["DOCUMENT_ROOT"] . "/includes/router.php";

function registerRoutes()
{
    get("/faculty/api/odRequest/f", function () {
        FacultyAuth::verify(2);
        echo json_encode(
            OdRequest::read(["classes.classID" => $_SESSION["classID"]])
        );
    });

    get("/faculty/api/odRequest/h", function () {
        FacultyAuth::verify(4);
        echo json_encode(
            OdRequest::readForHOD($_SESSION["departmentCode"])
        );
    });

    get("/faculty/api/odRequest/shh", function () {
        FacultyAuth::verify(3);
        echo json_encode(
            OdRequest::readForSHHOD()
        );
    });

    get('/faculty/api/odRequest/$classID', function ($classID) {
        FacultyAuth::verify(2);
        echo json_encode(OdRequest::read(["classes.classID" => $classID]));
    });

    post("/faculty/api/odRequest", function () {
        FacultyAuth::verify(2);
        $tempName = $_FILES["attachment"]["tmp_name"];
        $fileName = $_FILES["attachment"]["name"];
        $id = uniqid();
        $odID = $_SESSION['facultyID']."-".date("Y-m-d")."-".$id;
        $path =
          "/attachments/" . $id . "." . pathinfo($fileName, PATHINFO_EXTENSION);
        move_uploaded_file($tempName, $_SERVER["DOCUMENT_ROOT"] . $path);
        $odDetails = json_decode($_POST["odData"], true);
        OdRequest::commonCreate(
            [
            "odID" => $odID,
            "attachment" => $path,
            "statusID" => 1,
            "classID" => $_SESSION["classID"],
      ]
        );
        foreach ($odDetails as $odDetail) {
            $odDetail["odID"] = $odID;
            OdDetail::commonCreate($odDetail);
        }

        echo SuccessMessage::$add;
    });

    put('/faculty/api/odRequest/$odID', function ($odID) {
        FacultyAuth::verify(2);
        $update = json_decode(file_get_contents("php://input"), true);
        OdRequest::update(["odRequests.odID" => $odID], $update);
        echo SuccessMessage::$update;
    });

    delete('/faculty/api/odRequest/$odID', function ($odID) {
        FacultyAuth::verify(2);
        OdRequest::commonDelete(["odID" => $odID]);
        echo SuccessMessage::$delete;
    });
}
registerRoutes();
