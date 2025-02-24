<?php
include('./db/db.php');

$email_id= $_POST['email_id'];
$data=array();

$sql_feach_moods="SELECT * FROM `your_mood` WHERE email_id='$email_id'";
$result_feach_moods=mysqli_query($conn, $sql_feach_moods);
if (mysqli_num_rows($result_feach_moods)>0) {
    foreach ($result_feach_moods as $row) {
        $data[] = array(
            "mood_id" => $row['mood_id'],
            "mood_in_emoji_name" => $row['mood_in_emoji_name'],
            "time" => $row['time'],
            "fellings" => $row['fellings'],
        ); 
    }
    echo json_encode($data);
}
?>