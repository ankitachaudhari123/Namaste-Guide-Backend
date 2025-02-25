<?php
include('./db/db.php');

$email_id=$_POST['email_id'];

$delete_account="DELETE FROM `user_details` WHERE user_email_id='$email_id'";
if (mysqli_query($conn, $delete_account)) {
    echo json_encode("account delete successfully");
}else {
    echo json_encode("please try again");
}
?>