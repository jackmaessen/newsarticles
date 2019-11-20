<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Site Title   -->
<title>Newsarticles</title>

<!-- Bootstrap css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
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


<section class="section-margine blog-list">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-lg-9">               
				<div class="newsmessages">
					<h2>Newsmessages</h2>
					
					<?php include "includes/messages.php"; ?>
																			
				</div>
				
				
			</div> <!-- end col 9 -->
			<div class="col-md-3 col-lg-3">
				<div class="news-sidecolumn">
						<h3 class="page-header">Search article</h3>
						<!-- search form -->
						<form class="search-form" action="index.php" method="GET" role="form">							
							<input class="form-control" type="text" name="news_search" value="" placeholder="Search for..." />
							<br />							
							<button class="btn btn-primary" type="submit" name="submit">Search</button>													
						</form>
					
						<h3 class="page-header">Last messages</h3>
						<?php
						$counter = 0;
						
						foreach($newslist as $file){
							// open and prepare message
							$newsmsg = 'messages/';
							$newsmsg .= $file;
							$fh = fopen($msg, 'r');
							$txtmsg1 = file_get_contents($newsmsg);
							$txtmsg = stripslashes($txtmsg1);												
							// get data out of txt file								
							$lines = file($newsmsg, FILE_IGNORE_NEW_LINES);// filedata into an array												
							$news_title = $lines[1]; //  news title	
							?>
							<a href="index.php?page=<?php echo $lines[0]; ?>"><?php echo $news_title ; ?></a><br />
							<?php
							
							//echo $news_title.'<br />';
							
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

<!-- tinymce -->
<script>
tinyMCE.init({
	selector : ".tinymce",
	plugins: "emoticons link image code",	
	menubar: false,
	toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons | image | code',    
	height: 300,
	force_br_newlines : true,
	force_p_newlines : false,
	forced_root_block : '',
	
	images_upload_url : 'upload.php',
	automatic_uploads : false,
	
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

 

</script>

</body>
</html>

