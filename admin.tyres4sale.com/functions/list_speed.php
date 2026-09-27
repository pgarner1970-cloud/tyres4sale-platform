<?php
include('db.php');
header('Content-type: application/json');

$sql = "SELECT speedDesc FROM tblspeed ORDER BY speedDesc ASC";

$result = mysqli_query($conn,$sql);

$results_arr = array();

while( $row = mysqli_fetch_array($result) ){
    $speedDesc = $row['speedDesc'];
    $results_arr[] = array("speedDesc" => $speedDesc);
}

// encoding array to json format
echo json_encode($results_arr);