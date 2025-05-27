

  <section id="order-confirmation" class="page">
                <h2>Order Confirmation</h2>
                <div class="order-grid">
                    <!-- Sample Order Row -->
                    <?php include 'fetch_orders.php'; ?>
                     <!-- Add more order rows dynamically -->
                </div>
                <script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle Accept button click
    document.querySelectorAll('.accept-btn').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-order-id');
            updateOrderStatus(orderId, 2); // 2 for Accepted
        });
    });

    // Handle Reject button click
    document.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-order-id');
            updateOrderStatus(orderId, 3); // 3 for Rejected
        });
    });

    // AJAX request to update order status
    function updateOrderStatus(orderId, statusId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/gallarycafe/admin/odercon/update_order_status.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert('Order status updated successfully');
                location.reload(); // Reload to reflect changes
                window.location.href = '/gallarycafe/admin/index.php';
            } else {
                alert('Error updating order status');
            }
        };
        xhr.send('order_id=' + orderId + '&status_id=' + statusId);
    }
});
</script>

            </section>