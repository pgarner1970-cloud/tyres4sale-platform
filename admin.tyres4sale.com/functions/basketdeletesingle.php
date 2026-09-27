<?php
session_start();
include('db.php');

if(isset($_POST["id"])) {
	$query = $conn->prepare("DELETE FROM tblbasket WHERE basket_id=? AND EAN=?");
	$query->bind_param('ss', session_id(), $_POST["id"]);
	if($query->execute()) {
		echo "Tyre deleted from basket.";
	} else {
		echo "Error: ".$query->error;
	}
}