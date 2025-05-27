<?php
session_start(); // Start the session


if (isset($_SESSION['adminid'])) {
    $customer_id = $_SESSION['adminid'];
    
} else {
    session_destroy();

    // Redirect to the login page or homepage
    header("Location: http://localhost/gallarycafe/admin/login/loginpage.php");
    exit();
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The Gallery Café</title>
    <link rel="shortcut icon" href="/gallarycafe/images/logo.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
</head>




    <div class="sidebar">
       <div class="profile">
            <img src="data:image/png;base64,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" alt="Profile Picture" class="profile-pic">
            <h3 id="admin-name">Admin User</h3>
            
         </div>
           <nav>
            <ul>
                 <li class="active" data-page="home"><span>🏠</span> Home</li>
                 <li data-page="order-confirmation"><span>🛒</span> Order Confirmation</li>
                 <li data-page="offers"><span>🎁</span> Offers</li>
                 <li data-page="staff-management"><span>🛠️</span> Staff Management</li>
                 <li data-page="add-foods"><span>🍽️</span> Add Food Items</li>
                 <li data-page="logout"><span>🔒</span> Logout</li>
            </ul>
           </nav>
           <script>document.addEventListener('DOMContentLoaded', () => {
    const navItems = document.querySelectorAll('.sidebar nav ul li');
    const pages = document.querySelectorAll('.page');

    // Navigation Handling
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remove active class from all nav items
            navItems.forEach(nav => nav.classList.remove('active'));
            // Add active class to clicked nav item
            item.classList.add('active');

            // Hide all pages
            pages.forEach(page => page.classList.remove('active'));
            // Show the selected page
            const pageId = item.getAttribute('data-page');
            if (pageId === 'logout') {
                document.getElementById('logout').classList.add('active');

            } else {
                document.getElementById(pageId).classList.add('active');
            }
        });
    });

    // Logout Handling
    const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
    const cancelLogoutBtn = document.getElementById('cancel-logout-btn');

    confirmLogoutBtn.addEventListener('click', () => {
        // Implement actual logout logic here (e.g., redirect to login page)
        alert('You have been logged out.');
        window.location.href = '/gallarycafe/admin/login/logout.php';
    });

    cancelLogoutBtn.addEventListener('click', () => {
        // Navigate back to home page
        document.querySelector('.sidebar nav ul li.active').classList.remove('active');
        document.querySelector('.sidebar nav ul li[data-page="admin-home"]').classList.add('active');
        pages.forEach(page => page.classList.remove('active'));
        document.getElementById('admin-home').classList.add('active');
    });
});</script>
         </div>


        <!-- Staff Attributes Page -->
        
                  <!-- Home Page -->
                  <section id="home" class="page active"> 
                    <h2>Reservations</h2>
                    <div class="search-bar">
                        <input type="text" id="search-input" placeholder="Search by Customer Name or Booking Type..." onkeyup="searchTable()">  
                        <button id="search-button">Search</button>
                    </div>
                    <table id="reservation-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Customer Name</th>
                                <th>Booking Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'fetch_reservations.php'; // This file contains the PHP code to fetch and display data
                            ?>
                        </tbody>
                        <script>
                        function searchTable() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search-input");
    filter = input.value.toUpperCase();
    table = document.getElementById("reservation-table");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, except for the first row (headers), and hide those that don't match the search query
    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "none"; // Hide the row by default
        tdCustomer = tr[i].getElementsByTagName("td")[2]; // Customer Name column
        tdBooking = tr[i].getElementsByTagName("td")[3];  // Booking Type column

        if (tdCustomer || tdBooking) {
            txtValueCustomer = tdCustomer.textContent || tdCustomer.innerText;
            txtValueBooking = tdBooking.textContent || tdBooking.innerText;

            // Check if either the customer name or booking type matches the input
            if (txtValueCustomer.toUpperCase().indexOf(filter) > -1 || txtValueBooking.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = ""; // Show the row if it matches the search query
            }
        }
    }
}</script>
                    </table>
                </section>
                
        

                    <!-- Order Confirmation Page -->
                  

                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/admin/odercon/odercon.php');
?>
        
    

        <!-- Offers Page -->
      <!-- Offers Page -->
<!-- Offers Page -->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/admin/offer/offers.php');
?>





        <!-- Staff Management Page -->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/admin/staff/staff.php');
?>

        <!-- Add Food Items Page -->
     
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/admin/food/food.php');
?>

        <!-- Logout Page (Confirmation) -->
        <section id="logout" class="page">
            <h2>Logout</h2>
            <p>Are you sure you want to logout?</p>
            <button id="confirm-logout-btn">Yes, Logout</button>

            <button id="cancel-logout-btn">Cancel</button>
        </section>
    

    <script src="script.js"></script>

