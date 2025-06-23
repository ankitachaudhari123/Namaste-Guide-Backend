<?php
include('./db/db.php');
$mood_id= $_POST['mood_id'];
$fellings = $_POST['fellings'];
$mood = $_POST['mood'];

$sql_update_mood="UPDATE `your_mood` SET `fellings`='$fellings',`mood`='$mood' WHERE mood_id='$mood_id'";
if (mysqli_query($conn, $sql_update_mood)) {
    echo "success";
}else {
    echo "error";
}
?>