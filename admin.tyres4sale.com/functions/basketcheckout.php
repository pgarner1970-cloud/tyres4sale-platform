<?php
include('db.php');
session_start();
$user = session_id();

if ($_POST) {
    $username = $_POST["username"];
    $name = $_POST["order_name"];
    $email = $_POST["email"];
    $order_ref = $_POST["order_ref"];
    $notes = $_POST["notes"];
    $query = $conn->prepare("INSERT INTO tblOrderHdr (order_user,order_name,order_ref,order_notes) VALUES(?,?,?,?)");
    $query->bind_param('ssss', $username, $name, $order_ref, $notes);
    if($query->execute()) {
        $id = $query->insert_id;
    	$query = $conn->prepare("INSERT INTO tblOrderLines (order_id, Supplier, EAN, TyreDesc, UnitBuyPrice, UnitSellPrice, Quantity) SELECT ?, Supplier, EAN, TyreDesc, UnitBuyPrice, UnitSellPrice, Quantity FROM tblbasket WHERE basket_id=?");
        $query->bind_param('is', $id, $user);
        if($query->execute()) {
    	    $query = $conn->prepare("DELETE FROM tblbasket WHERE basket_id=?");
            $query->bind_param('s', $user);
            $query->execute();
    	    $query = $conn->prepare("UPDATE tblOrderHdr SET order_quantity=(SELECT SUM(Quantity) FROM tblOrderLines WHERE order_id=?) WHERE order_id=?");
            $query->bind_param('ii', $id, $id);
            $query->execute();
    	    $query = $conn->prepare("UPDATE tblOrderHdr SET order_value=(SELECT SUM(Quantity * UnitSellPrice) FROM tblOrderLines WHERE order_id=?) WHERE order_id=?");
            $query->bind_param('ii', $id, $id);
            $query->execute();
            $_SESSION['lastid'] = $id;
            // Send email
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
            $to = 'tyreorders@tyres4sale.com';
            $subject = 'New trade tyre order';
            
            $headers['From'] = 'webmaster@tyres4sale.com';
            $headers['MIME-Version'] = 'MIME-Version: 1.0';
            $headers['Content-type'] = 'text/html; charset=iso-8859-1';
            
            $message = $resultsHtml;
                        
            $returnval = mail($to, $subject, $message, $headers);    
            $to = 'paul@yellowarrow.co.uk';
            $returnval = mail($to, $subject, $message, $headers);    
            
            if (isset($email)){
                // Send mail to customer
                $sql = "SELECT * FROM tblOrderHdr WHERE order_id=" . $id;
                $result = mysqli_query($conn,$sql);
            
                $resultsHtml = "<table cellspacing=2 cellpadding=2 border=0>";
                $resultsHtml .= "<thead><tr bgcolor=#ddd style='font-weight:bold;'><td>Order</td><td>Date</td><td>Placed by</td><td>Reference</td><td>Quantity</td><td>Total Price (excl.VAT)</td></tr></thead>";
            
                while( $row = mysqli_fetch_array($result) ){
                    $resultsHtml .= "<tr style='border-top: 2px solid #ddd;'><th>" . $row['order_id'] . "</th><td>" . $row['order_date'] . "</td><td>" . $row['order_name'] . "</td>";
                    $resultsHtml .= "<td>" . $row['order_ref'] . "</td><td>" . $row['order_quantity'] . "</td><td>" . $row['order_value'] . "</td></tr>";
                    $resultsHtml .= "<tr style='font-weight:bold;'><td colspan=2>&nbsp;</td><td bgcolor=#f2f2f2>EAN</td><td bgcolor=#f2f2f2>TyreDesc</td><td bgcolor=#f2f2f2>Quantity</td><td bgcolor=#f2f2f2>Line Value</td>";
                    $resultsHtml .= "<td colspan=2>&nbsp;</td></tr>";
                    $sql = "SELECT EAN, TyreDesc, Quantity, UnitSellPrice, (UnitSellPrice * Quantity) line_value FROM tblOrderLines WHERE order_id=" . $row['order_id'] . " ORDER BY EAN";
                    $result2 = mysqli_query($conn,$sql);
                    while( $row2 = mysqli_fetch_array($result2) ){
                        $resultsHtml .= "<tr><td colspan=2>&nbsp;</td><td>" . $row2['EAN'] . "</td><td>" . $row2['TyreDesc'] . "</td>";
                        $resultsHtml .= "<td>" . $row2['Quantity'] . "</td><td>" . $row2['line_value'] . "</td>";
                        $resultsHtml .= '<td colspan=2>&nbsp;</td></tr>';
                    }       
                }
                $resultsHtml .= "</table>";
                $to = $email;
                $subject = 'LTC Tyres - Order Confirmation';
                
                $headers['From'] = 'webmaster@tyres4sale.com';
                $headers['MIME-Version'] = 'MIME-Version: 1.0';
                $headers['Content-type'] = 'text/html; charset=iso-8859-1';
                
                $message = $resultsHtml;
                            
                $returnval = mail($to, $subject, $message, $headers);
            }
            echo $id;
        } else {
    	    echo "Error: ".$query->error;
        }
    } else {
    	echo "Error: ".$query->error;
    }
}
	
