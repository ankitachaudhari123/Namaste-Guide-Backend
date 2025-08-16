<?php
header('Content-Type: application/json');
include('./db/db.php');

$inputJSON = file_get_contents("php://input");
$input = json_decode($inputJSON, true);

if (!$input) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON input"]);
    exit();
}

$user_email = $input['email'] ?? '';
$user_password = $input['password'] ?? '';

if (empty($user_email) || empty($user_password)) {
    echo json_encode(["status" => "error", "message" => "Please fill all fields"]);
    exit();
}

$sql = "SELECT * FROM `user_details` WHERE `user_email_id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
     if (password_verify($user_password, $user['password'])) {
        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user" => [
                "name" => $user['user_name'],
                "email" => $user['user_email_id'],
                "height" => $user['height'],
                "weight" => $user['weight'],
                "bmi" => $user['bmi'] ?? null,
                "bmi_status" => $user['bmi_status'] ?? null
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Wrong email please sign up."]);
}
?>