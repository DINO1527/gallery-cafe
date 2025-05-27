<?php
// Start the session to access session variables
session_start();

// Set response header to JSON
header('Content-Type: application/json');

// Include the database connection
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Retrieve and decode the POST data
// Assuming the data is sent as JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit();
}

$cart = $data['cart'] ?? [];
$order_type = $data['order_type'] ?? '';

$total_amount = $data['total_amount'] ?? 0;

// Validate order type
$allowed_order_types = ['takeaway', 'table_booked'];
if (!in_array(strtolower($order_type), $allowed_order_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid order type.']);
    exit();
}

// Validate cart
if (empty($cart)) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty.']);
    exit();
}

// Get current date and time
$order_date = date('Y-m-d');
$order_time = date('H:i:s');

// Initialize transaction
$conn->begin_transaction();

try {
    // Step 1: Insert into order_history to get the order_id
    $stmt = $conn->prepare("INSERT INTO order_history (customer_id, order_date, order_time, total_amount, status_id, order_type) VALUES (?, ?, ?, ?, ?, ?)");
    $initial_status_id = 1; // Assuming '1' is the initial status
    $total_amount = 0; // Placeholder for now, will update after order_items insertion
    $stmt->bind_param('issdis', $customer_id, $order_date, $order_time, $total_amount, $initial_status_id, $order_type);

    if (!$stmt->execute()) {
        throw new Exception("Failed to insert order history: " . $stmt->error);
    }

    // Get the inserted order_id
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Step 2: Insert into order_items and calculate total amount
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, food_id, quantity, sub_total) VALUES (?, ?, ?, ?)");

    foreach ($cart as $item) {
        $food_id = $item['id'];
        $quantity = $item['quantity'];
        $sub_total = $item['price'] * $quantity;
        $total_amount += $sub_total; // Accumulate the total amount

        // Bind the parameters and execute the query
        $stmt->bind_param('iiid', $order_id, $food_id, $quantity, $sub_total);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert order item: " . $stmt->error);
        }
    }

    $stmt->close();

    // Step 3: Update the total_amount in order_history after inserting all order_items
    $stmt = $conn->prepare("UPDATE order_history SET total_amount = ? WHERE order_id = ?");
    $stmt->bind_param('di', $total_amount, $order_id);

    if (!$stmt->execute()) {
        throw new Exception("Failed to update order history with total amount: " . $stmt->error);
    }

    $stmt->close();
    $_SESSION['order_id'] = $order_id;
    // Commit the transaction
    $conn->commit();

    // Respond with success
    echo json_encode(['success' => true, 'message' => 'Order placed successfully!', 'order_id' => $order_id]);
} catch (Exception $e) {
    // Rollback the transaction on error
    $conn->rollback();

    // Respond with error
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>