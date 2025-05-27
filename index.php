<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <title>Galarycafe-home</title>
    <link rel="shortcut icon" href="/gallarycafe/images/logo.png" type="image/png">

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
  <!-- font awesome style -->
  
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <link href="css/styles1.css" rel="stylesheet" />
  
  
</head>

<body>

 

  
    <!-- header section strats -->
    
    <?php require_once("navbar.php");?>
      
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
    <div class="bg-box">
      <img src="https://images3.alphacoders.com/109/1097928.jpg" alt="">
    </div>
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                     The Gallery Cafe Restaurant
                    </h1>
                    <p>
                    Experience the finest dining with our exquisite culinary creations. Order your favorite dishes and reserve your table online for an unforgettable experience
                    </p>
                    <div class="btn-box">
                      <a href="" class="btn1">
                      Explore Our Menu Reserve a Table
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      
          
        </div>
       
      </div>

    </section>
    <!-- end slider section -->
  

  <!-- offer section -->
  <section class="offer_section">
    <div class="offer_left">
        <h1 class="offer_text">Today's Best Offers</h1>
        <div class="offer_bar"></div>
    </div>
    <div class="offer_container">
      <div class="containerOFF">
            <div class="row" id="offer_row">
                <!-- Dynamic content will be added here -->
            </div>
            <div class="slider-nav" id="slider_nav">
                <!-- Navigation dots will be generated here -->
            </div>
        </div>
    </div>
</section>



  <!-- end offer section -->
  

  <!-- about section -->
  

  <iframe id="myIframe" src="final/" width="100%" style="border:none;"></iframe>

<script>
    function resizeIframe() {
        var iframe = document.getElementById("myIframe");
        if (iframe.contentWindow.document.body) {
            iframe.style.height = iframe.contentWindow.document.body.scrollHeight + "px";
        }
    }

    var iframe = document.getElementById("myIframe");
    iframe.addEventListener("load", resizeIframe);
</script>


    <section id="about">
    <div class="container">
      <h2>About Us</h2>
      <div class="about-content">
        <div class="about-item">
          <div class="about-image">
            <img src="images/o1.jpg" alt="Experienced Chefs">
          </div>
          <h3>Experienced Chefs</h3>
          <p>Our chefs bring years of experience to create the best culinary delights, ensuring top quality with every dish.</p>
        </div>
        <div class="about-item">
          <div class="about-image">
            <img src="https://media-cdn.tripadvisor.com/media/photo-s/0f/99/ba/e4/front-parking-lot-of.jpg" alt="Parking Area">
          </div>
          <h3>Parking Area</h3>
          <p>Convenient parking facilities for all our customers, with ample space for easy access and security.</p>
        </div>
        <div class="about-item">
          <div class="about-image">
            <img src="images/hero-bg.jpg" alt="Long & Short Dining">
          </div>
          <h3>Long & Short Dining</h3>
          <p>Choose between our cozy short dining spaces or larger areas for a grand dining experience, perfect for any occasion.</p>
        </div>
        <div class="about-item">
          <div class="about-image">
            <img src="https://images.pexels.com/photos/460537/pexels-photo-460537.jpeg?cs=srgb&dl=pexels-pixabay-460537.jpg&fm=jpg" alt="Table Arrangements">
          </div>
          <h3>Table Arrangements</h3>
          <p>Customized table arrangements for small and large groups, with the best settings to suit your preferences.</p>
        </div>
        
      </div>
    </div>
  </section>

<script src="script.js"></script>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/php/footer/footer.php');
 ?>
 <script src="js/script.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
  <!-- End Google Map -->
</body>
</html>