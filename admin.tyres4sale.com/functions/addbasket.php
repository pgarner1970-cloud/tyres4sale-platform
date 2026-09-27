<?php
session_start();
include('db.php');
$user = session_id();
if(isset($_POST["id"])) {
    $a =explode("~",$_POST["id"]);
	$query = $conn->prepare("SELECT TyreDesc, UnitBuyPrice, (UnitBuyPrice + tblrim.rimMarkupTrade) AS UnitTrade FROM (tbltyredata A Inner Join tblrim ON Diameter = tblrim.rimDesc) WHERE EAN=? AND Supplier=?");
	$query->bind_param('ss', $a[0], $a[1]);
	$query->execute();
	$result = $query->get_result();
	foreach($result as $row) {
		$desc = $row["TyreDesc"];
		$buyprice = $row["UnitBuyPrice"];
		$tradeprice = $row["UnitTrade"];
	}

	$query = $conn->prepare("INSERT INTO tblbasket (basket_id, Supplier, EAN, TyreDesc, UnitBuyPrice, UnitSellPrice, Quantity) VALUES (?,?,?,?,?,?,1) ON DUPLICATE KEY UPDATE Quantity=Quantity+1");
	$query->bind_param('ssssss', $user, $a[1], $a[0], $desc, $buyprice, $tradeprice);
	if($query->execute()) {
		echo "Tyre added to basket.";
	} else {
		echo "Error: ".$query->error;
	}
}