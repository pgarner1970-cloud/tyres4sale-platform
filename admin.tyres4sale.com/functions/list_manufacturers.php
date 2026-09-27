<?php
include('db.php');
header('Content-type: application/json');

// Return distinct manufacturers from tyre data,
// plus banding from tblmanufacturers (default Budget if missing).
$sql = "
  SELECT
    td.Manufacturer AS Manufacturer,
    COALESCE(tm.banding, 'Budget') AS banding
  FROM (
    SELECT DISTINCT Manufacturer
    FROM tbltyredata
    WHERE Manufacturer IS NOT NULL AND Manufacturer <> ''
    ORDER BY Manufacturer ASC
  ) td
  LEFT JOIN tblmanufacturers tm
    ON tm.Manufacturer = td.Manufacturer
";

$result = mysqli_query($conn, $sql);

$results_arr = array();

if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $results_arr[] = array(
      "Manufacturer" => $row["Manufacturer"],
      "banding" => $row["banding"]
    );
  }
}

// encoding array to json format
echo json_encode($results_arr);
