<?php
// /gallarycafe/php/user_dashboard/cancel_order.php

require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['order_id'])) {
    echo json_encode(['success' => false, 'error' => 'Order ID not provided.']);
    exit();
}

$order_id = intval($input['order_id']);
$user_id = $_SESSION['user_id'];

// Verify that the order belongs to the user and is not already cancelled
$stmt = $conn->prepare("SELECT status FROM orders WHERE order_id = ? AND customer_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$stmt->bind_result($status);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'error' => 'Order not found.']);
    $stmt->close();
    exit();
}
$stmt->close();

if (strtolower($status) === 'cancelled') {
    echo json_encode(['success' => false, 'error' => 'Order is already cancelled.']);
    exit();
}

// Update the order status to 'Cancelled'
$stmt = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE order_id = ? AND customer_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to cancel order.']);
}
$stmt->close();
?>
