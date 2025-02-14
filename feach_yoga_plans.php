<?php
include('./db/db.php');
$data=array();

$sql_feach_yoga_plans="SELECT * FROM `yoga_plans`";
$result_feach_yoga_plans=mysqli_query($conn, $sql_feach_yoga_plans);
if (mysqli_num_rows($result_feach_yoga_plans)>0) {
    foreach ($result_feach_yoga_plans as $row) {
        $data[] = array(
            "yoga_plan_id" => $row['yoga_plan_id'],
            "yoga_plan_name" => $row['yoga_plan_name'],
            "yoga_plan_image" => $row['yoga_plan_image'],
        );
       
    }
    echo json_encode($data);
}
?>