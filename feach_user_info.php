<?php
include('./db/db.php');

$email_id=$_POST['email_id'];
$data=array();

$sql_feach_user_info="SELECT * FROM `user_details` WHERE user_email_id='$email_id'";
$result_feach_user_info=mysqli_query($conn, $sql_feach_user_info);
if (mysqli_num_rows($result_feach_user_info>0)) {
    foreach ($result_feach_user_info as $row) {
        $data[] = array(
            "user_name" => $row['user_name'],
            "user_email_id" => $row['user_email_id'],
            "height" => $row['height'],
            "weight" => $row['weight'],
            "bmi" => $row['bmi'],
            "bmi_status" => $row['bmi_status'],
        );
    }
    echo json_encode($data);
}
?>