<?php
include('db.php');

if(isset($_POST["id"])) {
	$output = array();
	$query = $conn->prepare("SELECT * FROM tblrim WHERE rimRef = ?");
	$query->bind_param('i', $_POST["id"]);
	$query->execute();
	$result = $query->get_result();
	foreach($result as $row) {
		$output["rimdesc"] = $row["rimDesc"];
		$output["rimmarkup"] = $row["rimMarkupMailOrder"];
		$output["rimmarkupt"] = $row["rimMarkupTrade"];
		$output["rimtype"] = $row["rimType"];
	}
	echo json_encode($output);

}