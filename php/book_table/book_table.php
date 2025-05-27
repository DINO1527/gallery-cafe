<?php
// process_order.php

session_start();
header('Content-Type: application/json');

// Include only the database connection
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate received data
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
    exit();
}

// Check if the user is authenticated
if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_type = $data['order_type'] ?? '';
$total_amount = $data['total_amount'] ?? 0;
$cart = $data['cart'] ?? [];

// Get current date and time
$order_date = date('Y-m-d');
$order_time = date('H:i:s');

// Begin transaction
$conn->begin_transaction();

try {
    if (!empty($cart)) {
        // Insert into order_history
        $stmt = $conn->prepare("INSERT INTO order_history (customer_id, order_date, order_time, total_amount, status_id, order_type) VALUES (?, ?, ?, ?, ?, ?)");
        $status_id = 1; // Pending
        $stmt->bind_param("issdis", $customer_id, $order_date, $order_time, $total_amount, $status_id, $order_type);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert order history: " . $stmt->error);
        }
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert into order_items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, food_id, quanity, sub_total) VALUES (?, ?, ?, ?)");
        foreach ($cart as $item) {
            $food_id = $item['id'];
            $quantity = $item['quantity'];
            $sub_total = $item['price'] * $quantity;
            $stmt->bind_param("iiid", $order_id, $food_id, $quantity, $sub_total);
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert order item: " . $stmt->error);
            }
        }
        $stmt->close();

        // Store order_id in session
        $_SESSION['order_id'] = $order_id;
    }

    // Commit transaction
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
