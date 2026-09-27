<?php
include('db.php');
header('Content-type: application/json');

$sql = "SELECT rimDesc FROM tblrim ORDER BY rimDesc ASC";

$result = mysqli_query($conn,$sql);

$results_arr = array();

while( $row = mysqli_fetch_array($result) ){
    $rimDesc = $row['rimDesc'];
    $results_arr[] = array("rimDesc" => $rimDesc);
}

// encoding array to json format
echo json_encode($results_arr);