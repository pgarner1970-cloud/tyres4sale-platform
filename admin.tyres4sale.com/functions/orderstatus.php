<?php
include('db.php');
//header('Content-type: application/json');

if (isset($_GET[type])) {
    $type = $_GET[type];
    $sql = "SELECT * FROM tblOrderHdr WHERE (order_processed='" . $type . "' OR '*'='" . $type . "') ORDER BY order_id DESC LIMIT 100";
} else {
    $sql = "SELECT * FROM tblOrderHdr ORDER BY order_id DESC LIMIT 100";
}

$result = mysqli_query($conn,$sql);

$resultsHtml = "<div><button type='button' name='openorders' id='openorders' class='btn btn-primary openorders'>Open Orders</button>&nbsp;";
$resultsHtml .= "<button type='button' name='inprogress' id='inprogress' class='btn btn-warning inprogress'>In-progress Orders</button>&nbsp;";
$resultsHtml .= "<button type='button' name='complete' id='complete' class='btn btn-secondary complete'>Complete Orders</button>&nbsp;";
$resultsHtml .= "</div><div>&nbsp;</div>";
$resultsHtml .= "<table cellspacing=2 cellpadding=2 border=0 class='w-100'>";
$resultsHtml .= "<thead><tr bgcolor=#ddd style='font-weight:bold;'><td>Order</td><td>Date</td><td>Trade User</td><td>Placed by</td><td>Reference</td><td>Quantity</td><td>Total Price (excl.VAT)</td><td>Status</td><td>Action</td></tr></thead>";

while( $row = mysqli_fetch_array($result) ){
    $resultsHtml .= "<tr style='border-top: 2px solid #ddd;'><th>" . $row['order_id'] . "</th><td>" . $row['order_date'] . "</td><td>" . $row['order_user'] . "</td><td>" . $row['order_name'] . "</td>";
    $resultsHtml .= "<td>" . $row['order_ref'] . "</td><td>" . $row['order_quantity'] . "</td><td>" . $row['order_value'] . "</td>";
    $resultsHtml .= '<td>' . $row['order_processed'] . '</td><td>';
    //$resultsHtml .= "<button type='button' name='view' id='" . $row['order_id'] . "' class='btn btn-success btn-sm view'>View</button>";
    if ($type == "Open") {
        $resultsHtml .= "&nbsp;<button type='button' name='update' id='" . $row['order_id'] . "' class='btn btn-info btn-sm update'>Update</button>";
    } elseif ($type == "In-Progress") {
        $resultsHtml .= "&nbsp;<button type='button' name='update' id='" . $row['order_id'] . "' class='btn btn-info btn-sm update'>Update</button>";
    }
    $resultsHtml .= "</td></tr>";
    $resultsHtml .= "<tr style='font-weight:bold;'><td colspan=2>&nbsp;</td><td bgcolor=#f2f2f2>Supplier</td><td bgcolor=#f2f2f2>EAN</td><td bgcolor=#f2f2f2>TyreDesc</td><td bgcolor=#f2f2f2>Quantity</td><td bgcolor=#f2f2f2>Line Value</td>";
    $resultsHtml .= '<td colspan=2>&nbsp;</td></tr>';
     $sql = "SELECT Supplier, EAN, TyreDesc, Quantity, UnitSellPrice, (UnitSellPrice * Quantity) line_value FROM tblOrderLines WHERE order_id=" . $row['order_id'] . " ORDER BY Supplier, EAN";
    $result2 = mysqli_query($conn,$sql);
    while( $row2 = mysqli_fetch_array($result2) ){
        $resultsHtml .= "<tr><td colspan=2>&nbsp;</td><td>" . $row2['Supplier'] . "</td><td>" . $row2['EAN'] . "</td><td>" . $row2['TyreDesc'] . "</td>";
        $resultsHtml .= "<td>" . $row2['Quantity'] . "</td><td>" . $row2['line_value'] . "</td>";
        $resultsHtml .= '<td colspan=2>&nbsp;</td></tr>';
    }       
}
echo $resultsHtml;

