<?php

function displayCounter() {
    session_start();
    include('db.php');
    $sql = "SELECT SUM(Quantity) AS counter FROM tblbasket WHERE tblbasket.basket_id=?";
    $query = $conn->prepare($sql);
    $query->bind_param("s", session_id());
    $query->execute();
    $result = $query->get_result();
    $counter = $result->fetch_row()[0] ?? false;

    if ($counter > 0) {
        echo "<p class='noteBlue'>You have $counter tyres in your basket - <a href='basket.php'>View basket</a></p>";
    }
};

function displayOrderThanks() {
    session_start();
    include('db.php');
    if (isset($_SESSION["lastid"])) {
           echo "<p class='noteGreen'>Thank you for your order!</br>Your order number is " . $_SESSION["lastid"] . "</p>";
           unset($_SESSION['lastid']);
    }
};