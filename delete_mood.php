<?php
include('./db/db.php');

$mood_id=$_POST['mood_id'];

$delete_mood="DELETE FROM `your_mood` WHERE mood_id='$mood_id'";
if (mysqli_query($conn, $delete_mood)) {
   // echo json_encode("success");
   echo "success";
}else {
    echo json_encode("Failed to delete mood");
}
?>