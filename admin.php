<?php 
// check login
session_start();
if(!isset($_SESSION['news_login'])){
	header("Location: login.php");
	exit();
}

?>
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
<!-- resizing modal -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />
<!-- some basic styling -->
<link href="css/style.css" rel="stylesheet">
<!-- jQuery core -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <!-- Bootstrap js; need for modal --> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>


<!-- API key for TinyMCE -->
<script src="https://cdn.tiny.cloud/1/cu9iuv1soi8lkx4dfa0qp167qpr7pw81y9rj9n42dvtj1mch/tinymce/5/tinymce.min.js"></script> 
<!-- font awesome kit -->
<script src="https://kit.fontawesome.com/3a46605f9c.js" crossorigin="anonymous"></script>

</head>
<body>

<section>
    <div class="container">
        <div class="row">
			<div class="col-md-12 col-lg-12"> 
			<br />
				<a class="float-right" href="logout.php"><button class="btn btn-danger">Logout</button></a>
			</div>
		</div>
	</div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-lg-9">               
				<div class="newsmessages">
										
					<?php include "includes/admin-messages.php"; ?>
															
					<div class="form">
						<h3 class="page-header">Write Newsmessage</h3>
						<form action="admin.php" method="POST" role="form">
							
							<div class="control-group form-group">
								<div class="controls">
									<input class="form-control" name="image_dir" value="<?php echo $unique_id; ?>" type="hidden">
								</div>
							</div>
							
							<div class="control-group form-group">
								<div class="controls">
									<select class="category_selectbox form-control" name="category">
										<option value="">CATEGORY</option>
										<?php 
											$arr_length = count($all_categories); // count the number of categories
											for ($x = 0; $x < $arr_length; $x++) {
										?>
										<option class="news-category" value="<?php echo $all_categories[$x]; ?>"><?php echo $all_categories[$x]; ?></option>
										<?php 
										} 
										?>									 								
									</select>
								</div>
							</div>
							
							<div class="control-group form-group">
								<div class="controls">
									<input class="form-control" name="title" type="text" placeholder="TITLE" required>
								</div>
							</div>
							<div class="control-group form-group">
								<div class="controls">
									<input class="form-control" name="name" type="text" placeholder="AUTHOR">
								</div>
							</div>	
							
							<div class="control-group form-group">	
								<div class="controls">	
																
									<textarea id="tinymce" class="tinymce form-control custom-control" name="message" placeholder="MESSAGE"></textarea>
																				
								</div>
							</div>												
							<button type="submit" id="cf-submit" name="submit" class="btn btn-primary">SUBMIT</button>	
														
						</form>
					</div>
				</div>
				
				
			</div> <!-- end col 9 -->
			<div class="col-md-3 col-lg-3">
				<div class="news-sidecolumn">
				
					<h3 class="page-header">Filter category</h3>				
					<!-- filter form -->
					<form class="category-form form-inline" action="admin.php" method="GET" role="form">
						<div class="input-group mb-3">					
							<select class="category_selectbox form-control" name="filter_category">
								<?php 
									$arr_length = count($all_categories); // count the number of categories
									for ($x = 0; $x < $arr_length; $x++) {
								?>
								<option class="news-category" value="<?php echo $all_categories[$x]; ?>"><?php echo $all_categories[$x]; ?></option>
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
					<form class="search-form" action="admin.php" method="GET" role="form">							
						<input class="form-control" type="text" name="news_search" value="" placeholder="Search for...">													
						<button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button> 												
					</form>
					
					<h3 class="page-header">Last messages</h3>
					<?php
					$counter = 0;
					
					foreach($newslist as $file){
						// open and prepare message						
						$txtmsg1 = file_get_contents($newsmsg);
						$txtmsg = stripslashes($txtmsg1);												
						// get data out of txt file								
						$lines = file($file, FILE_IGNORE_NEW_LINES);// filedata into an array												
						$news_title = $lines[2]; //  news title	
						?>
						<a href="admin.php?page=<?php echo $lines[0]; ?>"><?php echo $news_title ; ?></a><br />
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

<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<!-- tinymce -->
<script>
tinyMCE.init({
	selector : ".tinymce",
	plugins: "emoticons link image code",	
	menubar: true,
	toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons | image | code',    
	height: 300,
	force_br_newlines : true,
	force_p_newlines : false,
	forced_root_block : '',
	paste_as_text: true,
	
	images_upload_url : 'upload.php',
	
	
	image_class_list: [
    {title: 'Responsive', value: 'img-responsive'}
    ],
	
	images_upload_handler : function(blobInfo, success, failure) {
		var xhr, formData;

		xhr = new XMLHttpRequest();
		xhr.withCredentials = false;
		xhr.open('POST', 'upload.php');

		xhr.onload = function() {
			var json;

			if (xhr.status != 200) {
				failure('HTTP Error: ' + xhr.status);
				return;
			}

			json = JSON.parse(xhr.responseText);

			if (!json || typeof json.file_path != 'string') {
				failure('Invalid JSON: ' + xhr.responseText);
				return;
			}

			success(json.file_path);
		};

		formData = new FormData();
		formData.append('file', blobInfo.blob(), blobInfo.filename());

		xhr.send(formData);
	},
	
    mobile: {
		theme: 'silver',
		plugins: 'emoticons link',
		toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons | image | code'
	}					
});

// make modal resizable and dragable
$( document ).ready(function() {
	$('.modal-content').resizable({
	  //alsoResize: ".modal-dialog",
	  minHeight: 300,
	  minWidth: 300
	});
	$('.modal-dialog').draggable();
	
});

// make Offside red
$(".news-category:contains(Offside)").addClass('text-danger');
</script>

</body>
</html>

