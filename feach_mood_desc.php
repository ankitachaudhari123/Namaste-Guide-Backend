<?php
include('./db/db.php');

$mood_id=$_POST['mood_id'];
$data= array();

$feach_mood_desc="SELECT * FROM `your_mood` WHERE mood_id='$mood_id'";
$result_mood_desc=mysqli_query($conn, $feach_mood_desc);
if (mysqli_num_rows($result_mood_desc)>0) {
    foreach ($result_mood_desc as $row) {
        $data[] = array(
            "fellings" => $row['fellings'],
            "mood" => $row['mood'],
            "date" => $row['date'],
            "time" => $row['time'],
        );  
    }
    echo json_encode($data);
}else{
    echo json_encode("data not found");  
}
?>