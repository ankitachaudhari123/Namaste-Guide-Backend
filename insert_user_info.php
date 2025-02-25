<?php
header('Content-Type: application/json');

include('./db/db.php');

$inputJSON = file_get_contents("php://input");
$input = json_decode($inputJSON, true);

if (!$input) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON input"]);
    exit();
}


$user_name = $input['user_name'] ?? '';
$user_email_id = $input['user_email_id'] ?? '';
$user_height = $input['user_height'] ?? '';
$user_weight = $input['user_weight'] ?? '';

if (empty($user_name) || empty($user_email_id) || empty($user_height) || empty($user_weight)) {
    echo json_encode(["status" => "error", "message" => "Please fill all information"]);
    exit();
}

$sql_check_user = "SELECT * FROM `user_details` WHERE user_email_id = ?";
$stmt = $conn->prepare($sql_check_user);
$stmt->bind_param("s", $user_email_id);
$stmt->execute();
$result_check_user = $stmt->get_result();

if ($result_check_user->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "You already have account"]);
    exit();
}

$insert_user_data = "INSERT INTO `user_details` (`user_name`, `user_email_id`, `height`, `weight`) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($insert_user_data);
$stmt->bind_param("ssdd", $user_name, $user_email_id, $user_height, $user_weight);

if ($stmt->execute()) {
    $bmi = $user_weight / ($user_height * $user_height);
    $bmi = round($bmi, 2);

    if ($bmi < 18.5) {
        $bmi_status = "Underweight";
    } elseif ($bmi < 24.9) {
        $bmi_status = "Normal weight";
    } elseif ($bmi < 29.9) {
        $bmi_status = "Overweight";
    } elseif ($bmi < 34.9) {
        $bmi_status = "Obesity Class 1 (Moderate)";
    } elseif ($bmi < 39.9) {
        $bmi_status = "Obesity Class 2 (Severe)";
    } else {
        $bmi_status = "Obesity Class 3 (Very Severe)";
    }

    $update_bmi = "UPDATE `user_details` SET `bmi` = ?, `bmi_status` = ? WHERE user_email_id = ?";
    $stmt = $conn->prepare($update_bmi);
    $stmt->bind_param("dss", $bmi, $bmi_status, $user_email_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User registered successfully", "bmi" => $bmi, "bmi_status" => $bmi_status]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update BMI"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to register user"]);
}

$conn->close();
?>
