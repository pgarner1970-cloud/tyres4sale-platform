<?php
session_start();
include('db.php');

if(isset($_POST["id"])) {
	$output = array();
	$query = $conn->prepare("SELECT Quantity, TyreDesc FROM tblbasket WHERE basket_id=? AND EAN=?");
	$query->bind_param('ss', session_id(), $_POST["id"]);
	$query->execute();
	$result = $query->get_result();
	foreach($result as $row) {
		$output["quantity"] = $row["Quantity"];
		$output["tyredesc"] = $row["TyreDesc"];
	}
	echo json_encode($output);

}