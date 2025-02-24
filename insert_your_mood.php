<?php
include('./db/db.php');
date_default_timezone_set('Asia/Kolkata');

$email_id=$_POST['email_id'];
$fellings=$_POST['fellings'];
$emoji_name=$_POST['emoji_name'];
$date=date('d-m-y');
$time=date('h-i-s');

$insert_your_mood="INSERT INTO `your_mood`(`email_id`, `fellings`, `mood_in_emoji_name`, `date`, `time`) VALUES ('$email_id','$fellings','$emoji_name','$date','$time')";
if (mysqli_query($conn, $insert_your_mood)) {
    echo json_encode("data insert succesfully"); 
}else{
    echo json_encode("error");
}
?>