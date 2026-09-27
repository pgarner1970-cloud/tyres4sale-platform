<?php
include('db.php');
header('Content-type: application/json');

$sql = "SELECT Supplier, LastUpdate FROM tbltyredataupdates ORDER BY LastUpdate ASC";

$result = mysqli_query($conn,$sql);

$results_arr = array();

while( $row = mysqli_fetch_array($result) ){
    $LastUpdate = $row['LastUpdate'];
    $Supplier = $row['Supplier'];
    $results_arr[] = array("Supplier" => $Supplier, "LastUpdate" => $LastUpdate);
}

// encoding array to json format
echo json_encode($results_arr);