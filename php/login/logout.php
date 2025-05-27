<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page or homepage
header("Location: /gallarycafe/php/login/loginpage.php");
exit();
?>
