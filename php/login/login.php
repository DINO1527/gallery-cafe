<?php

session_start();
include '../../dbconn.php'; // Corrected relative path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT customer_id, password FROM user_details WHERE name = ?");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($customer_id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct; set session variables
            $_SESSION['customer_id'] = $customer_id;
            $_SESSION['username'] = $username; // Optional: store username

            // Redirect to the home page
            header("Location: http://localhost/gallarycafe/index.php");
            
            exit();
            
        } else {
            // Incorrect password
            echo "<script>alert('Invalid username or password.'); window.history.back();</script>";
            $stmt->close();
            
            exit();
        }

        
    } else {  
        // User not found
        echo "<script>alert('Invalid username or password.'); window.history.back();</script>";
        $stmt->close();
        exit();
    }

  
}

$conn->close();

?>
