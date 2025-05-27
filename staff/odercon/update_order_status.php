<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

// Check if POST data is set
if (isset($_POST['order_id']) && isset($_POST['status_id'])) {
    $orderId = $_POST['order_id'];
    $statusId = $_POST['status_id'];

    // Update the order status in order_history table
    $sql = "UPDATE order_history SET status_id = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $statusId, $orderId);
    
    if ($stmt->execute()) {
        // If order type is 'table_booked', also update table_reservation status
        $sql_check = "SELECT order_type FROM order_history WHERE order_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $orderId);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        $row = $result->fetch_assoc();

        if ($row['order_type'] == 'table_booked') {
            $sql_table = "UPDATE table_reservation SET status_id = ? WHERE order_id = ?";
            $stmt_table = $conn->prepare($sql_table);
            $stmt_table->bind_param("ii", $statusId, $orderId);
            $stmt_table->execute();
        }

        echo "Order status updated successfully";
    } else {
        echo "Error updating order status: " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
