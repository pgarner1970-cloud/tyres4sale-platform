<?php
include('db.php');
header('Content-type: application/json');

$sql = "SELECT profileDesc FROM tblprofile ORDER BY profileDesc ASC";

$result = mysqli_query($conn,$sql);

$results_arr = array();

while( $row = mysqli_fetch_array($result) ){
    $profileDesc = $row['profileDesc'];
    $results_arr[] = array("profileDesc" => $profileDesc);
}

// encoding array to json format
echo json_encode($results_arr);