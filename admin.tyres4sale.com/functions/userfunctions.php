<?php
include('db.php');

if(isset($_POST["operation"])) {
	if($_POST["operation"] == "Add") {
		$username = $_POST["username"];
		$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
		$usertype = $_POST["usertype"];
		$description = $_POST["description"];
		$email = $_POST["email"];
		$query = $conn->prepare("INSERT INTO tblusers (username, hash, usertype, description, email) VALUES (?, ?, ?, ?, ?)");
		$query->bind_param('sssss', $username, $password, $usertype, $description, $email);
		if($query->execute()) {
			echo "User Added";
		} else {
			echo "Error: ".$query->error;
		}
	}
	
	if($_POST["operation"] == "Edit") {
		$userid = $_POST["user_id"];
		$username = $_POST["username"];
		$usertype = $_POST["usertype"];
		$description = $_POST["description"];
		$email = $_POST["email"];
		if (!empty($_POST["password"])) {
			$password= password_hash($_POST["password"], PASSWORD_DEFAULT);
			$query = $conn->prepare("UPDATE tblusers SET username=?, hash=?, usertype=?, description=?, email=? WHERE id=?");
			$query->bind_param('sssssi', $username, $password, $usertype, $description, $email, $userid);
		} else {
			$query = $conn->prepare("UPDATE tblusers SET username=?, usertype=?, description=?, email=? WHERE id=?");
			$query->bind_param('ssssi', $username, $usertype, $description, $email, $userid);
		}
		if($query->execute()) {
			echo "Data Updated";
		} else {
			echo "Error: ".$query->error;
		}
	}	
}
