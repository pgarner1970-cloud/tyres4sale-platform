<?php
include('db.php');

if(isset($_POST["operation"])) {
	if($_POST["operation"] == "Add") {
		$rimdesc = $_POST["rim_desc"];
		$rimmarkup = $_POST["rim_markup"];
		$rimmarkupt = $_POST["rim_markupt"];
		$rimtype = $_POST["rim_type"];
		$query = $conn->prepare("INSERT INTO tblrim (rimDesc, rimMarkupMailOrder, rimMarkupTrade, rimType) VALUES (?, ?, ?, ?)");
		$query->bind_param('ssss', $rimdesc, $rimmarkup, $rimmarkupt, $rimtype);
		if($query->execute()) {
			echo "Data Inserted";
		} else {
			echo "Error: ".$query->error;
		}
	}
	
	if($_POST["operation"] == "Edit") {
		$rimdesc = $_POST["rim_desc"];
		$rimmarkup = $_POST["rim_markup"];
		$rimmarkupt = $_POST["rim_markupt"];
		$rimtype = $_POST["rim_type"];
		$rimid = $_POST["rim_id"];
		$query = $conn->prepare("UPDATE tblrim SET rimDesc=?, rimMarkupMailOrder=?, rimMarkupTrade=?, rimType=? WHERE rimRef=?");
		$query->bind_param('ssssi', $rimdesc, $rimmarkup, $rimmarkupt, $rimtype, $rimid);
		if($query->execute()) {
			echo "Data Updated";
		} else {
			echo "Error: ".$query->error;
		}
	}	
}
