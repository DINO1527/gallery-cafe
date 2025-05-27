<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $number_of_tables = $_POST['number_of_tables'] ?? 1;
    $number_of_persons = $_POST['number_of_persons'] ?? 1;
    $time = $_POST['time'] ?? '00:00:00';
    $date = $_POST['date'] ?? date('Y-m-d');

    // Determine book_type
    $book_type = !empty($order_items) ? $order_id : '0';

    // Set status_id to 1 (Pending)
    $status_id = 1;

    // Insert into table_reservation
    $stmt = $conn->prepare("INSERT INTO table_reservation (order_id, customer_id, table_count, checkin_time, checkin_date, status_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siissi", $book_type, $customer_id, $number_of_tables, $time, $date, $status_id);

    if ($stmt->execute()) {
        // Success
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "success",
                    title: "Table Booked",
                    text: "Your table has been booked successfully.",
                }).then(() => {
                    // Redirect to home page or order history
                    window.location.href = "/gallarycafe/index.php";
                });
            });
        </script>';

        // Clear order_id from session if exists
        if ($order_id) {
            unset($_SESSION['order_id']);
        }
    } else {
        // Error
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to book the table. Please try again.",
                });
            });
        </script>';
    }

    $stmt->close();
}

$conn->close();
?>