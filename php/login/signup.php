<?php
session_start();
require_once '../../dbconn.php'; // Use require_once for critical files
$_SESSION['customer_id'] = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone_no = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic input validation
    if (empty($username) || empty($email) || empty($phone_no) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.history.back();</script>";
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // Optional: Enforce password strength (e.g., minimum length)
    if (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters long.'); window.history.back();</script>";
        exit();
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT customer_id FROM user_details WHERE name = ? OR email = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . htmlspecialchars($conn->error));
        echo "<script>alert('An error occurred. Please try again later.'); window.history.back();</script>";
        exit();
    }

    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Determine which field is duplicated
        $stmt->bind_result($existing_customer_id);
        $stmt->fetch();

        // Fetch the existing user's details to check which field matches
        $stmt_check = $conn->prepare("SELECT name, email FROM user_details WHERE customer_id = ?");
        if ($stmt_check) {
            $stmt_check->bind_param("i", $existing_customer_id);
            $stmt_check->execute();
            $stmt_check->bind_result($existing_username, $existing_email);
            $stmt_check->fetch();
            $stmt_check->close();

            if ($existing_username === $username && $existing_email === $email) {
                $error_message = "Username and Email are already taken.";
            } elseif ($existing_username === $username) {
                $error_message = "Username is already taken.";
            } else {
                $error_message = "Email is already registered.";
            }

            echo "<script>alert('$error_message'); window.history.back();</script>";
            exit();
        } else {
            // If unable to prepare the second statement
            echo "<script>alert('An error occurred. Please try again later.'); window.history.back();</script>";
            exit();
        }
    }

    $stmt->close();

    // Password hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $stmt = $conn->prepare("INSERT INTO user_details (name, email, phone_no, password) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Prepare failed: " . htmlspecialchars($conn->error));
        echo "<script>alert('An error occurred. Please try again later.'); window.history.back();</script>";
        exit();
    }

    $stmt->bind_param("ssss", $username, $email, $phone_no, $hashed_password);

    if ($stmt->execute()) {
        // Signup successful, set session variables
        $_SESSION['customer_id'] = $conn->insert_id; // Get the last inserted ID
        $_SESSION['username'] = $username; // Optional: store username

        // Redirect to the desired page (e.g., home page)
        header("Location: http://localhost/gallarycafe/php/login/loginpage.php");
        $stmt->close();
        exit();
    } else {
        // Handle insertion errors (e.g., duplicate entry due to race condition)
        error_log("Execute failed: " . htmlspecialchars($stmt->error));
        echo "<script>alert('An error occurred during signup. Please try again later.'); window.history.back();</script>";
        $stmt->close();
        exit();
    }

    
}

$conn->close();
?>
