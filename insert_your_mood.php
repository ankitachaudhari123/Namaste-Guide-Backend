<?php
header('Content-Type: application/json');
include('./db/db.php');
date_default_timezone_set('Asia/Kolkata');

// Read raw JSON
$raw = file_get_contents("php://input");
if ($raw === false || $raw === '') {
    echo json_encode(["status" => "error", "message" => "No JSON input received"]);
    exit();
}

$input = json_decode($raw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON format"]);
    exit();
}

// Grab + trim. Feelings optional.
$email_id = isset($input['email_id']) ? trim($input['email_id']) : '';
$mood     = isset($input['mood']) ? trim($input['mood']) : '';
$feelings = isset($input['feelings']) ? trim($input['feelings']) : ''; // allow empty

if ($email_id === '' || $mood === '') {
    echo json_encode(["status" => "error", "message" => "Missing required fields (email_id, mood)."]);
    exit();
}

$date = date('Y-m-d');
$time = date('H:i:s'); // 24h recommended

// Use prepared statement
$sql = "INSERT INTO `your_mood`(`email_id`, `fellings`, `mood`, `date`, `time`) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Statement prepare failed",
        "error"   => mysqli_error($conn)
    ]);
    exit();
}

mysqli_stmt_bind_param($stmt, "sssss", $email_id, $feelings, $mood, $date, $time);

if (mysqli_stmt_execute($stmt)) {
    $insert_id = mysqli_insert_id($conn); // works even if PK column has custom name
    echo json_encode([
        "status"    => "success",
        "message"   => "Data inserted successfully",
        "insert_id" => $insert_id,
        "data"      => [
            "id"        => $insert_id,
            "email_id"  => $email_id,
            "mood"      => $mood,
            "feelings"  => $feelings,
            "date"      => $date,
            "time"      => $time,
        ],
    ]);
} else {
    echo json_encode([
        "status"  => "error",
        "message" => "Database insertion failed",
        "error"   => mysqli_error($conn)
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
