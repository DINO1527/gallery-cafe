
	/*------------------
		Navigation
	
	/*---------------
		Search
	
	/*-------------------
		Hero Slider
	-------------------*/
	$('.hero-slider').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 1,
		centerMode: true,
		variableWidth: true,
		centerMode: true,
		arrows: false,
		autoplay: true,
		pauseOnHover:false,
		autoplaySpeed: 3000,
		responsive: [
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});
	
	var hero_slider = $('.hero-slider');

	

	

	

	
	