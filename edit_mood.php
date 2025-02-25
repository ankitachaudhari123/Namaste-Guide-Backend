<?php
include('./db/db.php');
date_default_timezone_set('Asia/Kolkata');

$mood_id=$_POST['mood_id'];
$fellings=$_POST['fellings'];
$mood=$_POST['mood'];
$mood_in_emoji_name=$_POST['mood_in_emoji_name'];
$date=date('d-m-y');
$time=date('h-i-s');


$edit_mood="UPDATE `your_mood` SET `fellings`='$fellings',`mood`='$mood',`mood_in_emoji_name`='$mood_in_emoji_name',`date`='$date',`time`='$time' WHERE mood_id='$mood_id'";
if (mysqli_query($conn, $edit_mood)) {
    echo json_encode("done");
}else {
    echo json_encode("please try again");
}
?>