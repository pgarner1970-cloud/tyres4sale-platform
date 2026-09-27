<?php
session_start();
include('db.php');

if($_POST["operation"] == "Edit") {
	$quantity = $_POST["quantity"];
	$basket_id = $_POST["basket_id"];
	$query = $conn->prepare("UPDATE tblbasket SET Quantity=? WHERE basket_id=? AND EAN=?");
	$query->bind_param('sss', $quantity, session_id(), $basket_id);
	if($query->execute()) {
		echo "Basket quantity updated";
	} else {
		echo "Error: ".$query->error;
	}
}
