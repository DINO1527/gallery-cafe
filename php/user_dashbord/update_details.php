<?php
session_start(); // Start the session
include('../../dbconn.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['customer_id'])) {
        echo json_encode(['error' => 'User not authenticated']);
        exit;
    }

    $customer_id = $_SESSION['customer_id'];

    // Decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Retrieve and sanitize input
    $address = isset($input['address']) ? trim($input['address']) : '';
    $phone_no = isset($input['phone_no']) ? trim($input['phone_no']) : '';
    $user_image = isset($input['user_image']) ? trim($input['user_image']) : null; // Updated variable

    // Validation
    if (empty($address) || empty($phone_no)) {
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }

    if (!preg_match('/^[0-9]{10}$/', $phone_no)) {
        echo json_encode(['error' => 'Phone number must be 10 digits']);
        exit;
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update address and phone_no
        $sql = "UPDATE user_details SET address = ?, phone_no = ? WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }
        $stmt->bind_param("ssi", $address, $phone_no, $customer_id);
        if (!$stmt->execute()) {
            throw new Exception('Error updating profile: ' . $stmt->error);
        }
        $stmt->close();

        // If user_image is provided, update it
        if ($user_image) { // Updated condition
            $sql_pic = "UPDATE user_details SET user_image = ? WHERE customer_id = ?";
            $stmt_pic = $conn->prepare($sql_pic);
            if (!$stmt_pic) {
                throw new Exception('Database error: ' . $conn->error);
            }
            // Decode Base64 string to binary data
            $user_image_binary = base64_decode($user_image);
            $stmt_pic->bind_param("bi", $user_image_binary, $customer_id); // 'bi' for blob and integer

            // Send longblob data in chunks
            $stmt_pic->send_long_data(0, $user_image_binary);

            if (!$stmt_pic->execute()) {
                throw new Exception('Error updating profile picture: ' . $stmt_pic->error);
            }
            $stmt_pic->close();
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => 'Profile updated successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
