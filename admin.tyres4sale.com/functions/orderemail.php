<?php
include('db.php');

function orderemail($id) {
    $id=7;
    $sql = "SELECT * FROM tblOrderHdr WHERE order_id=" . $id;
    $result = mysqli_query($conn,$sql);

    $resultsHtml = "<table cellspacing=2 cellpadding=2 border=0>";
    $resultsHtml .= "<thead><tr bgcolor=#ddd style='font-weight:bold;'><td>Order</td><td>Date</td><td>Trade User</td><td>Placed by</td><td>Reference</td><td>Quantity</td><td>Total Price (excl.VAT)</td></tr></thead>";

    while( $row = mysqli_fetch_array($result) ){
        $resultsHtml .= "<tr style='border-top: 2px solid #ddd;'><th>" . $row['order_id'] . "</th><td>" . $row['order_date'] . "</td><td>" . $row['order_user'] . "</td><td>" . $row['order_name'] . "</td>";
        $resultsHtml .= "<td>" . $row['order_ref'] . "</td><td>" . $row['order_quantity'] . "</td><td>" . $row['order_value'] . "</td></tr>";
        $resultsHtml .= "<tr style='font-weight:bold;'><td colspan=2>&nbsp;</td><td bgcolor=#f2f2f2>Supplier</td><td bgcolor=#f2f2f2>EAN</td><td bgcolor=#f2f2f2>TyreDesc</td><td bgcolor=#f2f2f2>Quantity</td><td bgcolor=#f2f2f2>Line Value</td>";
        $resultsHtml .= "<td colspan=2>&nbsp;</td></tr>";
        $sql = "SELECT Supplier, EAN, TyreDesc, Quantity, UnitSellPrice, (UnitSellPrice * Quantity) line_value FROM tblOrderLines WHERE order_id=" . $row['order_id'] . " ORDER BY Supplier, EAN";
        $result2 = mysqli_query($conn,$sql);
        while( $row2 = mysqli_fetch_array($result2) ){
            $resultsHtml .= "<tr><td colspan=2>&nbsp;</td><td>" . $row2['Supplier'] . "</td><td>" . $row2['EAN'] . "</td><td>" . $row2['TyreDesc'] . "</td>";
            $resultsHtml .= "<td>" . $row2['Quantity'] . "</td><td>" . $row2['line_value'] . "</td>";
            $resultsHtml .= '<td colspan=2>&nbsp;</td></tr>';
        }       
    }
    $resultsHtml .= "</table>";
    $to = 'paul@yellowarrow.co.uk';
    $subject = 'New trade tyre order';
    
    $headers['From'] = 'webmaster@tyres4sale.com';
    $headers['MIME-Version'] = 'MIME-Version: 1.0';
    $headers['Content-type'] = 'text/html; charset=iso-8859-1';
    
    $message = $resultsHtml;
                
    $res = mail($to, $subject, $message, $headers);
}