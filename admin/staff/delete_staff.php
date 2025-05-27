<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Delete staff from database
    $sql = "DELETE FROM staff WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        echo "Staff member deleted successfully!";
    } else {
        echo "Error deleting staff member: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
