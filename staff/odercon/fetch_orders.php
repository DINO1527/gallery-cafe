<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');


// SQL query to retrieve order details and food items
$sql = "SELECT 
            order_history.order_date, order_history.order_time, order_history.order_type,order_history.order_id, 
            GROUP_CONCAT(food_menu.food_name SEPARATOR ', ') AS food_items, 
            GROUP_CONCAT(order_items.quantity SEPARATOR ', ') AS quantities, 
            status.status_name, user_details.name AS customer_name,
            table_reservation.checkin_time, table_reservation.checkin_date, table_reservation.table_count,table_reservation.status_id
        FROM order_history 
        JOIN order_items ON order_history.order_id = order_items.order_id
        JOIN food_menu ON order_items.food_id = food_menu.food_id
        JOIN status ON order_history.status_id = status.status_id
        JOIN user_details ON order_history.customer_id = user_details.customer_id
        LEFT JOIN table_reservation ON order_history.order_id = table_reservation.order_id
        WHERE order_history.status_id = 1 OR table_reservation.status_id = 1
        GROUP BY order_history.order_id";

// Execute the query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data for each row
    while($row = $result->fetch_assoc()) {
        echo "<div class='order-row'>
                <div class='order-cell'>" . $row["order_date"] . "</div>
                <div class='order-cell'>" . $row["order_time"] . "</div>
                <div class='order-cell'>" . $row["food_items"] . "</div>
                <div class='order-cell'>" . $row["quantities"] . "</div>
                <div class='order-cell'>" . $row["order_type"] . "</div>
                <div class='order-actions'>
                <button class='accept-btn' data-order-id='" . $row["order_id"] . "'>Accept</button>
                <button class='reject-btn' data-order-id='" . $row["order_id"] . "'>Reject</button>    
                </div>
              </div>";

        // If the order type is "booked_table", display table reservation details
        if ($row["order_type"] == "table_booked") {
            echo "<div class='order-row'>
                    <div class='order-cell'>Customer Name: " . $row["customer_name"] . "</div>
                    <div class='order-cell'>Check-in Time: " . $row["checkin_time"] . "</div>
                    <div class='order-cell'>Check-in Date: " . $row["checkin_date"] . "</div>
                    <div class='order-cell'>Table Count: " . $row["table_count"] . "</div>
                    <div class='order-cell'>order id: " . $row["order_id"] . "</div>
                  </div>
                  <div class='order-actions'>
                  <button class='accept-btn' data-order-id='" . $row["order_id"] . "'>Accept</button>
                  <button class='reject-btn' data-order-id='" . $row["order_id"] . "'>Reject</button>
      
                </div>";
        }
    }
} else {
    echo "<div class='order-row'><div class='order-cell' colspan='6'>No orders found</div></div>";
}

// Close the connection
//$conn->close();
?>
