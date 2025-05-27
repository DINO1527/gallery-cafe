<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

if (isset($_GET['id'])) {
    $food_id = $_GET['id'];

    // Delete the image from food_img
    $sql_img = "DELETE FROM food_img WHERE food_id = '$food_id'";
    $conn->query($sql_img);

    // Delete the offer from food_menu
    $sql_menu = "DELETE FROM food_menu WHERE food_id = '$food_id'";
    if ($conn->query($sql_menu) === TRUE) {
        echo "Offer deleted successfully.";
        header("Location: /gallarycafe/admin/index.php"); // Redirect to the offer page
    } else {
        echo "Error deleting offer: " . $conn->error;
    }
}

//$conn->close();
?>
