<?php
session_start();
include('../../dbconn.php');

header('Content-Type: application/json');

// For demonstration purposes, setting customer_id manually.
// In a real application, this should be set during user login.


$customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;

if ($customer_id) {
    // Fetch user details from database
    $sql = "SELECT name, address, phone_no, email, user_image FROM user_details WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'Database error: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8');
        $phone_no = htmlspecialchars($row['phone_no'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8');
        $user_image = !empty($row['user_image']) ? 'data:image/jpeg;base64,' . base64_encode($row['user_image']) : '/gallarycafe/images/user.png';

        echo json_encode([
            'logged_in' => true,
            'name' => $name,
            'address' => $address,
            'phone_no' => $phone_no,
            'email' => $email,
            'user_image' => $user_image
        ]);
    } else {
        // No profile found, use default values
        echo json_encode([
            'logged_in' => true,
            'name' => '',
            'address' => '',
            'phone_no' => '',
            'email' => '',
            'user_image' => 'user.png'
        ]);
    }
} else {
    // Not logged in
    echo json_encode(['logged_in' => false]);
}
?>
