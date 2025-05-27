<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

// Define your SQL query
$sql = "SELECT order_history.order_date, order_history.order_time, user_details.name AS customer_name, 
               order_history.order_type, status.status_name 
        FROM order_history 
        JOIN status ON order_history.status_id = status.status_id 
        JOIN user_details ON order_history.customer_id = user_details.customer_id";

// Execute the query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data for each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["order_date"] . "</td>
                <td>" . $row["order_time"] . "</td>
                <td>" . $row["customer_name"] . "</td>
                <td>" . $row["order_type"] . "</td>
                <td>" . $row["status_name"] . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No reservations found</td></tr>";
}

// Close the connection
//$conn->close();
?>
