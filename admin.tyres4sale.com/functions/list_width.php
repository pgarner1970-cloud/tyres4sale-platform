<?php
include('db.php');
header('Content-type: application/json');

$sql = "SELECT widthDesc FROM tblwidth ORDER BY widthDesc ASC";

$result = mysqli_query($conn,$sql);

$results_arr = array();

while( $row = mysqli_fetch_array($result) ){
    $widthDesc = $row['widthDesc'];
    $results_arr[] = array("widthDesc" => $widthDesc);
}

// encoding array to json format
echo json_encode($results_arr);