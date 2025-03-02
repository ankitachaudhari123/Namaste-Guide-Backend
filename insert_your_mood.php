<?php
header('Content-Type: application/json');
include('./db/db.php');
date_default_timezone_set('Asia/Kolkata');

$inputJSON = file_get_contents("php://input");

if (empty($inputJSON)) {
    echo json_encode(["status" => "error", "message" => "No JSON input received"]);
    exit();
}

$input = json_decode($inputJSON, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON format"]);
    exit();
}

if (empty($input['email_id']) || empty($input['mood']) || empty($input['feelings'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit();
}

$email_id = mysqli_real_escape_string($conn, $input['email_id']);
$feelings = mysqli_real_escape_string($conn, $input['feelings']);
$mood = mysqli_real_escape_string($conn, $input['mood']);
$date = date('Y-m-d'); 
$time = date('H:i:s');

$insert_your_mood = "INSERT INTO `your_mood`(`email_id`, `fellings`, `mood`, `date`, `time`) VALUES ('$email_id','$feelings','$mood','$date','$time')";

if (mysqli_query($conn, $insert_your_mood)) {
    echo json_encode(["status" => "success", "message" => "Data inserted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database insertion failed", "error" => mysqli_error($conn)]);
}
?>
