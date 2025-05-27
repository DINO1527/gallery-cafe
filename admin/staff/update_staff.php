<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update staff details
    $sql = "UPDATE staff SET email=?, password=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $hashed_password, $username);

    if ($stmt->execute()) {
        echo "Staff details updated successfully!";
    } else {
        echo "Error updating staff details: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
