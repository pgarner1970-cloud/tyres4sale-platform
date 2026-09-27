<?php
session_start();
include('db.php');
header('Content-type: application/json');

$sql = "SELECT * FROM tblOrderHdr WHERE order_user='" . $_SESSION['username'] . "' ORDER BY order_id DESC LIMIT 20";

$result = mysqli_query($conn,$sql);

$results_arr = array();

while( $row = mysqli_fetch_array($result) ){
    $order_id = $row['order_id'];
    $order_date = $row['order_date'];
    $order_processed = $row['order_processed'];
    $order_name = $row['order_name'];
    $order_ref = $row['order_ref'];
     //$results_arr[] = array("order_id" => $order_id, "order_date" => $order_date, "order_processed" => $order_processed);
    $results_arr[] = $row;
}

// encoding array to json format
echo json_encode($results_arr);