<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Site Title   -->
<title>News-slider</title>
<!-- Bootstrap css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/slick-theme.css">
<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
<!-- Slick slider CDN-->
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script src="http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- font awesome kit -->
<script src="https://kit.fontawesome.com/3a46605f9c.js" crossorigin="anonymous"></script>

<body>


<section class="section">
    <div class="container">
		<h3 class="text-center">Newsslider</h3>
		<br />
        <div class="row regular slider">		
			<?php include 'includes/slidermessages.php'; ?>					
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

