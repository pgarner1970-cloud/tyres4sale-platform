<?php
session_start();
include('db.php');

if(isset($_POST["id"])) {
	$output = array();
	$query = $conn->prepare("SELECT * FROM tblusers WHERE id = ?");
	$query->bind_param('i', $_POST["id"]);
	$query->execute();
	$result = $query->get_result();
	foreach($result as $row) {
		$output["id"] = $row["id"];
		$output["username"] = $row["username"];
		$output["usertype"] = $row["usertype"];
		$output["description"] = $row["description"];
		$output["email"] = $row["email"];
	}
	echo json_encode($output);
    exit();
}

if(isset($_SESSION["username"])) {
	$output = array();
	$query = $conn->prepare("SELECT * FROM tblusers WHERE username = ?");
	$query->bind_param('s', $_SESSION["username"]);
	$query->execute();
	$result = $query->get_result();
	foreach($result as $row) {
		$output["username"] = $row["username"];
		$output["usertype"] = $row["usertype"];
		$output["description"] = $row["description"];
		$output["email"] = $row["email"];
	}
	echo json_encode($output);

}