<?php
session_start();
include('db.php');
header('Content-type: application/json');

$sql = "SELECT Supplier, EAN, Quantity, TyreDesc, UnitSellPrice, (UnitSellPrice * Quantity) line_total FROM tblOrderLines WHERE order_id=" . $_POST["id"] . " ORDER BY Supplier, EAN";

$result = mysqli_query($conn,$sql);

$data = array();

while( $row = mysqli_fetch_array($result) ){    
	$results_arr = array();
	
    $results_arr[] = $row['Supplier'];
	$results_arr[] = $row['EAN'];
    $results_arr[] = $row['TyreDesc'];
    $results_arr[] = $row['UnitSellPrice'];
    $results_arr[] = $row['Quantity'];
    $results_arr[] = $row['line_total'];
	$data[] = $results_arr;
}

// encoding array to json format
echo json_encode($data);
