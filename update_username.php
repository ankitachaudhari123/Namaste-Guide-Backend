<?php
include('./db/db.php');

$username=$_POST['username'];
$email_id=$_POST['email_id'];

$update_username="UPDATE `user_details` SET `user_name`='$username' WHERE user_email_id='$email_id'";
if (mysqli_query($conn, $update_username)) {
    echo json_encode("data update successfully");
}else{
    echo json_encode("error");
}
?>