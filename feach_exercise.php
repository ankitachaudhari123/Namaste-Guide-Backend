<?php
include('./db/db.php');

$yoga_plan_id=$_POST['yoga_plan_id'];
$data=array();

$sql_feach_exercise="SELECT yoga_plans.yoga_plan_name, exercise.yoga_exercise_id , exercise.exercise_name, exercise.exercise_cover_img FROM yoga_plans INNER JOIN exercise ON yoga_plans.yoga_plan_id = exercise.yoga_plan_id WHERE yoga_plans.yoga_plan_id='$yoga_plan_id';";
$result_feach_exercise=mysqli_query($conn, $sql_feach_exercise);
if (mysqli_num_rows($result_feach_exercise)>0) {
    foreach ($result_feach_exercise as $row) {
        $data[] = array(
            "yoga_exercise_id" => $row['yoga_exercise_id'],
            "exercise_name" => $row['exercise_name'],
            "exercise_cover_img" => $row['exercise_cover_img'],
            "yoga_plan_name" => $row['yoga_plan_name'],
        );
    }
    echo json_encode($data);
}
?>