<?php
function get_total_all_records() {
	include('db.php');
	
	$sql = "SELECT * FROM tblrim";
	$result = mysqli_query($conn,$sql);
	return $result->num_rows;
}