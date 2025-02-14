<?php
include('./db/db.php');

$user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
$user_email_id = mysqli_real_escape_string($conn, $_POST['user_email_id']);
$user_height = mysqli_real_escape_string($conn, $_POST['user_height']);
$user_weight = mysqli_real_escape_string($conn, $_POST['user_weight']);

if ($user_name == '' || $user_email_id == '' || $user_height == '' || $user_weight == '') {
    echo json_encode("Please Fill All Information");
} else {
    // check user have alredy accound or not
    $sql_check_user="SELECT * FROM `user_details` WHERE user_email_id='$user_email_id'";
    $result_check_user=mysqli_query($conn, $sql_check_user);
    if (mysqli_num_rows($result_check_user)>0) {
        echo json_encode("You Have alredy Accound");
    }else {
        $insert_user_data = "INSERT INTO `user_details`(`user_name`, `user_email_id`, `height`, `weight`) VALUES ('$user_name', '$user_email_id', '$user_height', '$user_weight')";
        if (mysqli_query($conn, $insert_user_data)) {
            $bmi = $user_weight / ($user_height * $user_height);
            $bmi=round($bmi, 2);
        
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

            $update_bmi = "UPDATE `user_details` SET `bmi`='$bmi', `bmi_status`='$bmi_status' WHERE user_email_id='$user_email_id'";
            if (mysqli_query($conn, $update_bmi)) {
                echo json_encode("update");
            } else {
                echo json_encode("error to update");
            }
        } else {
            echo json_encode("error");
        }
    }
}
?>
