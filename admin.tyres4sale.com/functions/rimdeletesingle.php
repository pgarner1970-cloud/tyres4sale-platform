<?php
include('db.php');

if(isset($_POST["id"])) {
	$query = $conn->prepare("DELETE FROM tblrim WHERE rimRef = ?");
	$query->bind_param('i', $_POST["id"]);
	if($query->execute()) {
		echo "Data deleted !";
	} else {
		echo "Error: ".$query->error;
	}
}