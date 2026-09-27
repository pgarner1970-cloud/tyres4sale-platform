<?php
include('db.php');
header('Content-type: application/json');

$sql = "SELECT Supplier,EAN,TyreDesc,UnitBuyPrice,SUM(Quantity) Qty FROM tblOrderLines ln INNER JOIN tblOrderHdr hd ON ln.order_id=hd.order_id WHERE order_processed='Open' GROUP BY Supplier,EAN";

$result = mysqli_query($conn,$sql);

$results_arr = array();

while( $row = mysqli_fetch_array($result) ){
    $Supplier = $row['Supplier'];
    $EAN = $row['EAN'];
    $TyreDesc = $row['TyreDesc'];
    $UnitBuyPrice = $row['UnitBuyPrice'];
    $Qty = $row['Qty'];
    $results_arr[] = array("Supplier" => $Supplier, "EAN" => $EAN, "TyreDesc" => $TyreDesc, "UnitBuyPrice" => $UnitBuyPrice, "Qty" => $Qty);
}

// encoding array to json format
echo json_encode($results_arr);