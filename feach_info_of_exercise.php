<?php
include('./db/db.php');

$yoga_exercise_id = $_POST['yoga_exercise_id'];
$data=array();

$sql_feach_info = "SELECT * FROM `exercise` WHERE yoga_exercise_id='$yoga_exercise_id'";
$result_feach_info=mysqli_query($conn, $sql_feach_info);
if (mysqli_num_rows($result_feach_info)>0) {
   foreach ($result_feach_info as $row) {
    $data[] = array(
        "exercise_name" => $row['exercise_name'],
        "exercise_img" => $row['exercise_img'],
        "exercise_desc" => $row['exercise_desc'],
    );
   }
   echo json_encode($data);
}
?>