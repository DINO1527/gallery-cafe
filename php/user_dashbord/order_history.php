<?php

// Include the database connection
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');
session_start();

// Set header for JSON response
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Prepare the SQL statement with JOIN to get status_name
$sql = "
    SELECT 
        oh.order_id, 
        oh.order_date, 
        oh.order_time, 
        s.status_name 
    FROM 
        order_history oh
    JOIN 
        status s ON oh.status_id = s.status_id
    WHERE 
        oh.customer_id = ?
    ORDER BY 
        oh.order_date DESC, 
        oh.order_time DESC
";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Check if preparation was successful
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Database prepare error: ' . $conn->error]);
    exit();
}

// Bind parameters
$stmt->bind_param("i", $customer_id);

// Execute the statement
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'error' => 'Database execute error: ' . $stmt->error]);
    $stmt->close();
    exit();
}

// Get the result
$result = $stmt->get_result();

// Initialize orders array
$orders = [];

// Fetch data
while ($row = $result->fetch_assoc()) {
    $orders[] = [
        'order_id'       => $row['order_id'],
        'order_date'     => $row['order_date'],
        'order_time'     => $row['order_time'],
        'status'         => $row['status_name']
    ];
}

// Close the statement
$stmt->close();

// Return the JSON response
echo json_encode(['success' => true, 'orders' => $orders]);
?>
