
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Site Title   -->
<title>Newsarticles</title>

<!-- Bootstrap css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<!-- some basic styling -->
<link href="css/style.css" rel="stylesheet">
<!-- jQuery core -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- API key for TinyMCE -->
<script src="https://cdn.tiny.cloud/1/cu9iuv1soi8lkx4dfa0qp167qpr7pw81y9rj9n42dvtj1mch/tinymce/5/tinymce.min.js"></script> 
<!-- font awesome kit -->
<script src="https://kit.fontawesome.com/3a46605f9c.js" crossorigin="anonymous"></script>

</head>
<body>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-lg-9">               
				<div class="newsmessages">
					
					<?php include "includes/messages.php"; ?>
																			
				</div>
								
			</div> <!-- end col 9 -->
			<div class="col-md-3 col-lg-3">
				<div class="news-sidecolumn">
				
					<h3 class="page-header">Filter category</h3>				
					<!-- filter form -->
					<form class="category-form form-inline" action="index.php" method="GET" role="form">
						<div class="input-group mb-3">					
							<select class="category_selectbox form-control" name="filter_category">
								<?php 
									$arr_length = count($all_categories); // count the number of categories							
									for ($x = 0; $x < ($arr_length - 1); $x++) { // -1; strip out cat Offside
								?>
								<option value="<?php echo $all_categories[$x]; ?>"><?php echo $all_categories[$x]; ?></option>
								<?php 
								} 							
								?>									 								
							</select>
						
							<div class="input-group-append">		
								<button class="btn btn-primary" type="submit">Filter</button>
							</div>	
						</div>							
					</form>
					
					<h3 class="page-header">Search messages</h3>
					<!-- search form -->
					<form class="search-form" action="index.php" method="GET" role="form">							
						<input class="form-control" type="text" name="news_search" value="" placeholder="Search for...">													
						<button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button> 												
					</form>
					
					<h3 class="page-header">Last messages</h3>
					<?php
					$counter = 0;
					
					foreach($filtered_newslist as $file){
						// open and prepare message									
						$txtmsg1 = file_get_contents($file);
						$txtmsg = stripslashes($txtmsg1);												
						// get data out of txt file								
						$lines = file($file, FILE_IGNORE_NEW_LINES);// filedata into an array												
						$news_title = $lines[2]; //  news title	
						?>
						<a href="index.php?page=<?php echo $lines[0]; ?>"><?php echo $news_title ; ?></a><br />
						<?php
						
						fclose($fh);
						// limit titles to $maxtitls
						$counter++;
						if($counter == $maxtitls) {
							break;
						}
					} // end foreach
					
					?>
					
				</div>
				
			</div>
						
		</div> <!-- end row -->
	</div> <!-- end container -->

</section>



</body>
</html>

