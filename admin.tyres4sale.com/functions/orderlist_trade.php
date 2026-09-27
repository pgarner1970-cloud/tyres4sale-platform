<?php
session_start();
include('db.php');
header('Content-type: application/json');

$sql = "SELECT tblbasket.*, tbltyredata.Manufacturer FROM tblbasket INNER JOIN tbltyredata ON tblbasket.EAN = tbltyredata.EAN AND tblbasket.Supplier = tbltyredata.Supplier WHERE tblbasket.basket_id ='".session_id()."'";

$result = mysqli_query($conn,$sql);

$data = array();

while( $row = mysqli_fetch_array($result) ){    
	$results_arr = array();
	
	$results_arr[] = $row['EAN'];
    $results_arr[] = $row['Supplier'];
    $results_arr[] = $row['TyreDesc'];
    $results_arr[] = $row['UnitSellPrice'];
    $results_arr[] = $row['Quantity'];
    $results_arr[] = $row['UnitSellPrice'];
    $results_arr[] = $row['Manufacturer'];
	//$results_arr[] = '<button type="button" name="update" id="' . $row['EAN'] . '" class="btn btn-warning btn-xs update">Update</button>';
	//$results_arr[] = '<button type="button" name="delete" id="' . $row['EAN'] . '" class="btn btn-danger btn-xs delete">Delete</button>';

	$data[] = $results_arr;
}

// encoding array to json format
echo json_encode($data);