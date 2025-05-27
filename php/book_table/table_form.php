
<?php
// book_table.php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    // Redirect to login page with SweetAlert
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "error",
                title: "Authentication Error",
                text: "You need to be logged in to book a table.",
            }).then(() => {
                window.location.href = "/gallarycafe/index.php";
            });
        });
    </script>';
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_id = $_SESSION['order_id'] ?? null; // Assuming 'process_order.php' sets 'order_id' in session
$order_items = [];
$total_amount = 0;

if ($order_id) {
    // Fetch order items from database
    $stmt = $conn->prepare("SELECT fm.food_name, oi.quantity, oi.sub_total FROM order_items oi JOIN food_menu fm ON oi.food_id = fm.food_id WHERE oi.order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $order_items[] = $row;
            $total_amount += $row['sub_total'];
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galarycafe - Book a Table</title>
    <link rel="shortcut icon" href="/gallarycafe/images/logo.png" type="image/png">

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="/gallarycafe/css/bootstrap.css" />
    <!-- Custom Styles -->
    <link rel="stylesheet" href="/gallarycafe/css/style.css">
    <link rel="stylesheet" href="/gallarycafe/css/styles_booktbl.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Responsive CSS -->
    <link href="/gallarycafe/css/responsive.css" rel="stylesheet" />
</head>
<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/navbar.php');
 ?>
<section class="book_section layout_padding">
    <div class="container">
        <div class="heading_container2">
            <h2>Book A Table</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form_container">
                    <form id="reservationForm" method="POST" action="table_form.php">
                        <?php if (!empty($order_items)): ?>
                            <div id="foodOrderSummary" style="display: block;">
                                <h5>Your Selected Food Order:</h5>
                                <ul id="foodOrderList">
                                    <?php foreach ($order_items as $item): ?>
                                        <li><?php echo htmlspecialchars($item['food_name']); ?> - Quantity: <?php echo htmlspecialchars($item['quantity']); ?> - Subtotal: $<?php echo number_format($item['sub_total'], 2); ?></li>
                                    <?php endforeach; ?>
                                    <li><strong>Total: $<?php echo number_format($total_amount, 2); ?></strong></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <div id="foodOrderSummary" style="display: none;">
                                <h5>Your Selected Food Order:</h5>
                                <p>No food items ordered.</p>
                            </div>
                        <?php endif; ?>

                        <!-- Table Reservation Options -->
                        <div class="form-group">
                            <select class="form-control nice-select wide" name="number_of_tables" id="number_of_tables" required>
                                <option value="" disabled selected>How many tables?</option>
                                <option value="1">1 Table</option>
                                <option value="2">2 Tables</option>
                                <option value="3">3 Tables</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control nice-select wide" name="number_of_persons" id="number_of_persons" required>
                                <option value="" disabled selected>How many persons?</option>
                                <option value="2">2 Persons</option>
                                <option value="3">3 Persons</option>
                                <option value="4">4 Persons</option>
                                <option value="5">5 Persons</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="time" name="time" id="time" class="form-control" placeholder="Select Time" required />
                        </div>

                        <div class="form-group">
                            <input type="date" name="date" id="date" class="form-control" placeholder="Select Date" required />
                        </div>

                        <div class="btn_box">
                            <button type="submit" class="btn btn-primary">Book Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once('save_table.php')
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/php/footer/footer.php');
 ?>
</body>
</html>
