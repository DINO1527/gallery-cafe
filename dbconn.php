<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bat08";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Set the charset to handle binary data correctly
$conn->set_charset("utf8mb4");
?>

