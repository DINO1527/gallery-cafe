<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

if (isset($_GET['id'])) {
    $food_id = $_GET['id'];

    // Get the current data of the offer
    $sql = "SELECT food_menu.food_name, food_menu.discription, food_menu.price, food_menu.discount 
            FROM food_menu 
            WHERE food_menu.food_id = '$food_id'";
    $result = $conn->query($sql);
    $offer = $result->fetch_assoc();

    // Return offer data as JSON
    echo json_encode($offer);
}

$conn->close();
?>
