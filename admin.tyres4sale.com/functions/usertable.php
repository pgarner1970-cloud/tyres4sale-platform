<?php
include('db.php');
include('usertablecount.php');

header('Content-type: application/json');

$sql = "SELECT * FROM tblusers ";

if(isset($_POST["search"]["value"])) {
	$sql .= 'WHERE username LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if(isset($_POST["order"])) {
	$sql .= 'ORDER BY ' .$_POST["order"]["0"]["column"] . ' ' .$_POST["order"]["0"]["dir"]. ' ';
}
else
{	
	$sql .= 'ORDER BY username ASC ';
}

if($_POST["length"] != -1) {
	$sql .= 'LIMIT '.$_POST["start"] . ', ' .$_POST["length"];
} 

$result = mysqli_query($conn,$sql);
$filtered_rows = $result->num_rows;

$data = array();

while( $row = mysqli_fetch_array($result) ){    
	$results_arr = array();
	
	$results_arr[] = $row['id'];
    $results_arr[] = $row['username'];
    $results_arr[] = $row['usertype'];
    $results_arr[] = $row['email'];
    $results_arr[] = $row['description'];
	$results_arr[] = '<button type="button" name="update" id="' . $row['id'] . '" class="btn btn-warning btn-sm update">Update</button>';
	$results_arr[] = '<button type="button" name="delete" id="' . $row['id'] . '" class="btn btn-danger btn-sm delete">Delete</button>';

	$data[] = $results_arr;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"		=>	$filtered_rows,
	"recordsFiltered"	=>	get_total_all_records(),
	"data"				=>	$data
);

// encoding array to json format
echo json_encode($output);
