<?php
include('./db/db.php');

$height=$_POST['height'];
$weight=$_POST['weight'];
$email_id=$_POST['email_id'];

$update_height_weight="UPDATE `user_details` SET `height`='$height',`weight`='$weight'WHERE user_email_id='$email_id'";
if (mysqli_query($conn, $update_height_weight)) {
    $bmi = $weight / ($height * $height);
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
    $stmt->bind_param("dss", $bmi, $bmi_status, $email_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User registered successfully", "bmi" => $bmi, "bmi_status" => $bmi_status]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update BMI"]);
    }
}

?>