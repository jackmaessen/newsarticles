<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Site Title   -->
<title>News-slider</title>
<!-- jQuery core -->
<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>

<!-- Bootstrap css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

<!-- font awesome kit -->
<script src="https://kit.fontawesome.com/3a46605f9c.js" crossorigin="anonymous"></script>

<!-- Slick theme css -->
<link rel="stylesheet" type="text/css" href="css/slick-theme.css">
<!-- Slick slider CDN-->
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- Some basic styling -->
<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>


<section class="col-md-3-slider">
    <div class="container">
		<h2 class="text-center">Newsslider</h2>
		<br />
        <div class="row regular slider">		
			<?php include('includes/slidermessages.php'); ?>	 
		</div>				
	</div>	
</section>

<!-- js voor slider -->
<script type="text/javascript">
    $(document).on('ready', function() {                      
      $(".regular").slick({
        dots: false,
		centerMode: true,
		centerPadding: '0px',
		autoplay: true,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
		responsive: [
		  {
			breakpoint: 960,
			settings: {
			  arrows: true,
			  slidesToShow: 2
			}
		  },
		  {
			breakpoint: 768,
			settings: {
			  arrows: false,
			  slidesToShow: 1
			}
		  }
		]
      });                 
    });
</script>

</body>
</html>

