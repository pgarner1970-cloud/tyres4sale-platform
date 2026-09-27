<?php
session_start();
include('db.php');

$query = $conn->prepare("DELETE FROM tblbasket WHERE basket_id=?");
$query->bind_param('s', session_id());
if($query->execute()) {
	echo "Basket has been cleared.";
} else {
	echo "Error: ".$query->error;
}