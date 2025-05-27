<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="/gallarycafe/css/style.css">



<?php
session_start(); // Start the session

// Check if the customer_id is set
if (isset($_SESSION['customer_id'])) {
    $customer_id = $_SESSION['customer_id'];
   
   
} else {
    
}
?>

<?php require_once(__DIR__ . "/php/user_dashbord/user_dashbord.php"); ?>
<section>
 <header class="header_section">
   <div class="container">
    <nav class="navbar navbar-expand-lg custom_nav-container">
      <a class="navbar-brand" href="index.php">
        <span><img class="logo" src="/gallarycafe/images/logo.png" alt=""></span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <a class="nav-link" href="/gallarycafe/index.php">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/gallarycafe/php/menu/menu.php">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/gallarycafe/gallary_view/gallary.php">Gallary</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/gallarycafe/php/book_table/table_form.php">Book Table</a>
          </li>
        </ul>
        <div class="user_option">
              <a href="#" class="open-dashboard-btn" id="open-dashboard-btn">
                   <i class="fa fa-user" aria-hidden="true"></i>
              </a>

            <?php if (isset($_SESSION['customer_id'])): ?>
                   <!-- If user is logged in, show Order Now button -->
               <a href="/gallarycafe/php/menu/menu.php" class="order_online">
                  Order Now
               </a>
           <?php else: ?>
                  <!-- If user is not logged in, show Login button -->
                <a href="/gallarycafe/php/login/loginpage.php" class="login_btn">
                 Login
                </a>
           <?php endif; ?>
    
</div>
          </div>
        </nav>
      </div>
 </header>
</section>
<!-- Add your sections here -->



<!-- Bootstrap JS and dependencies -->
<script src="/gallarycafe/js/dashbord.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  // JavaScript to set the active class on the navbar
  $(document).ready(function() {
    // Get current URL path
    var path = window.location.pathname;
    // Loop through nav links
    $('.navbar-nav .nav-item .nav-link').each(function() {
      // Check if href matches current path
      if ($(this).attr('href') === path) {
        $(this).parent().addClass('active'); // Set active class
      }
    });
  });

</script>