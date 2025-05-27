<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galarycafe-home</title>
    <link rel="shortcut icon" href="/gallarycafe/images/logo.png" type="image/png">

<title> Feane </title>

<!-- bootstrap core css -->
<link rel="stylesheet" type="text/css" href="/gallarycafe/css/bootstrap.css" />
<link rel="stylesheet" href="/gallarycafe/css/styles_menu.css">
<!--owl slider stylesheet -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<!-- nice select  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- font awesome style -->

<!-- Custom styles for this template -->
<link href="/gallarycafe/css/style.css" rel="stylesheet" />
<!-- responsive style -->
<link href="/gallarycafe/css/responsive.css" rel="stylesheet" />


</head>
<body>

<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/navbar.php');
 ?>

<section class="food_section layout_padding-bottom">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>Our Menu</h2>
        </div>
<section class="top">
        
        <div class="search-container">
               <input type="text" placeholder="Search..." class="search-box">
              <button type="submit" class="search-btn">Search</button>
        </div> 

        <div class="view_card">
        <button type="button" id="popupBtn" class="cart-button">
            <i class="fa fa-shopping-cart"></i> View Cart
        </button>
       </div>
        </section>
        <?php
    
        // Fetch categories
        $sql = "SELECT category_name FROM food_category";
        $result = $conn->query($sql);

        // Start the `<ul>` structure
        echo '<ul class="filters_menu">';
        echo '<li class="active" data-filter="*">All</li>'; // Always display "All" option

        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                $categoryName = strtolower($row['category_name']); // Use lowercase for filter class
                echo '<li data-filter=".' . $categoryName . '">' . $row['category_name'] . '</li>';
            }
        } else {
            echo '<li>No categories found</li>';
        }

        echo '</ul>';
        require_once('food_menu.php');

?>

    </div>
</section>


<!-- Cart Button -->
<section class="a">

    <!-- Cart Popup -->
    <div id="popupPage" class="popup">
        <div class="popup_content">
            <span class="close" id="closeBtn">&times;</span>
            <h2>Your Cart</h2>
            <div class="cart-container">
                <div id="cartItems">
               
                   <!-- Cart items will be dynamically inserted here -->
                </div>
            </div>
            <div>
                <strong>Total: $<span id="cartTotal">0.00</span></strong>
            </div>
            <button id="confirmCart" class="confirm-button">Confirm Order</button>
        </div>
    </div>

     <!-- Confirmation Popup -->
     <div id="confirmationPopup" class="popup">
        <div class="popup_content">
            <span class="close closeConfirmBtn" id="closeConfirmBtn">&times;</span>
            <h2>Choose an Option</h2>
            <button id="takeAway" class="option-button">Take Away</button>
            <button id="bookTable" class="option-button">Book a Table</button>
        </div>
    </div>
</section>
    
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/php/footer/footer.php');
 ?>
 

  <script src="/gallarycafe/js/script.js"></script>
  <script src="/gallarycafe/js/menu.js"></script>
</body>
</html>

