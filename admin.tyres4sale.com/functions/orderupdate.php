<?php
include('db.php');

if(isset($_POST["id"])) {
	$query = $conn->prepare("SELECT order_processed FROM tblOrderHdr WHERE order_id=?");
	$query->bind_param('i', $_POST["id"]);
	if($query->execute()) {
	    $result = $query->get_result();
	    foreach($result as $row) {
		    $status_now = $row["order_processed"];
	    }
	    if ($status_now == "Open") {
	        $status_new = "In-Progress";
	    }
	    if ($status_now == "In-Progress") {
	        $status_new = "Complete";
	    }
	    $query = $conn->prepare("UPDATE tblOrderHdr SET order_processed=? WHERE order_id=?");
	    $query->bind_param('si', $status_new, $_POST["id"]);
        if($query->execute()) {
        	echo "Order updated.";
        } else {
        	echo "Error: ".$query->error;
        }
    }
}