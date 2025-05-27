document.addEventListener("DOMContentLoaded", () => {
    fetch('offer.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            generateOffers(data);
        })
        .catch(error => console.error('Error fetching data:', error));
});

function generateOffers(offers) {
    if (!Array.isArray(offers)) {
        console.error('Data is not an array:', offers);
        return;
    }

    const offerRow = document.getElementById("offer_row");
    const sliderNav = document.getElementById("slider_nav");
    offerRow.innerHTML = ""; // Clear existing content
    sliderNav.innerHTML = ""; // Clear existing dots

    let currentIndex = 0;
    let autoSlideInterval;

    // Create dots for each offer
    offers.forEach((offer, index) => {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        if (index === currentIndex) dot.classList.add('active'); // Set initial active dot
        dot.addEventListener('click', () => {
            showOffer(index);
            resetAutoSlide();
        });
        sliderNav.appendChild(dot);
    });

    // Function to show the current offer with overlapping transition
    function showOffer(index) {
        const previousOffer = document.querySelector(".col-md-6.active");
        const nextOffer = document.createElement("div");
        nextOffer.className = "col-md-6";
        nextOffer.style.opacity = "0"; // Start hidden

        const offer = offers[index];
        nextOffer.innerHTML = `
            <div class="box1">
                <div class="img-box1">
                    <img src="${offer.image}" alt="Offer Image">
                </div>
                <div class="detail-box1">
                    <h5>${offer.food_name}</h5>
                    <h6 class="pulse"><span>${offer.discount}</span> Off</h6>
                    <a class="pulse" href="#">Order Now</a>
                </div>
                <div class="blob"></div>
            </div>
        `;

        offerRow.appendChild(nextOffer);

        // Start the animations with overlapping transitions
        if (previousOffer) {
            previousOffer.style.animation = "slideOutLeft 0.8s forwards"; // Slide out to left
            previousOffer.classList.remove("active");
        }

        // Animate the next card sliding in
        nextOffer.style.animation = "slideInLeft 0.8s forwards"; // Slide in from right
        nextOffer.classList.add("active");

        // Update active dot
        document.querySelectorAll(".dot").forEach(dot => dot.classList.remove('active'));
        document.querySelectorAll(".dot")[index].classList.add('active');
    }

    // Auto-slide offers every 5 seconds
    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            currentIndex = (currentIndex + 1) % offers.length;
            showOffer(currentIndex);
        }, 5000);
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    // Initial display of the first offer
    showOffer(currentIndex);
    startAutoSlide();
}



    
    






// Scroll-triggered animations
window.addEventListener('scroll', function() {
    const fadeIns = document.querySelectorAll('.fade-in');
    const slideIns = document.querySelectorAll('.slide-in-left');
    const rotateIns = document.querySelectorAll('.rotate-in');

    fadeIns.forEach(el => {
        if (isInView(el)) {
            el.style.opacity = 1;
        }
    });

    slideIns.forEach(el => {
        if (isInView(el)) {
            el.style.transform = 'translateX(0)';
        }
    });

    rotateIns.forEach(el => {
        if (isInView(el)) {
            el.style.transform = 'rotateY(0)';
        }
    });
});

function isInView(element) {
    const rect = element.getBoundingClientRect();
    return rect.top >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight);
}


$(document).ready(function() {
    // Handle click event on nav links
    $('.navbar-nav .nav-item .nav-link').on('click', function() {
      // Remove 'active' class from all nav items
      $('.navbar-nav .nav-item').removeClass('active');
      // Add 'active' class to the clicked nav item
      $(this).parent().addClass('active');
    });
  });
