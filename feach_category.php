<?php
include('./db/db.php');

$yoga_plan_id=$_POST['yoga_plan_id'];
$data=array();

$sql_feach_catrgory="SELECT * FROM `divide_exercise_for_category` WHERE yoga_plan_id='$yoga_plan_id'";
$result_feach_catrgory=mysqli_query($conn, $sql_feach_catrgory);
if (mysqli_num_rows($result_feach_catrgory)>0) {
    foreach ($result_feach_catrgory as $row) {
        $data[] = array(
            "category_id" => $row['category_id'],
            "yoga_plan_id" => $row['yoga_plan_id'],
            "category_name" => $row['category_name'],
            "image" => $row['image'],
        );
    }
    echo json_encode($data);
}
?>