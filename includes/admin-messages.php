<?php
include('settings.php');
include('includes/search.php');

// name block
if (isset($_POST['name'])) {
	$safenewsinput = htmlentities($_POST['name']);
	if($safenewsinput == NULL){
		$newsauthor = 'Anonymous';								
	} else {
		$newsauthor = $safenewsinput;
	}
}

// title block
if (isset($_POST['title'])) {
	$safenewsinput = htmlentities($_POST['title']);
	if($safenewsinput == NULL){
		$newstitle = 'No Title';
	} else {
		$newstitle = $safenewsinput;
	}
}

// message block
if (isset($_POST['message'])) {
	$safenewsinput = htmlentities($_POST['message']);
	$usermessage = $safenewsinput;
	if ($usermessage == NULL){				
		?>
			<div class="alert alert-danger"><i class="fa fa-times"></i>&nbsp;&nbsp;Message can't be empty</div>
		<?php
		
	} else {
		// set name and content of message file; each message has unique id
		$unique_id = date('YmdHis');
		
		// put content in .txt file with linebreaks; unique_id first
		$newsinput = $unique_id.PHP_EOL;
		$newsinput .= $newstitle.PHP_EOL;						
		$newsinput .= $dateformat.PHP_EOL; // determined in settings
		$newsinput .= $newsauthor.PHP_EOL;
		$newsinput .= $usermessage.PHP_EOL;
								
		$newsfile = 'messages/';
		$newsfile .= $unique_id . '.txt'; //name of the file is the same as unique_id

		// mail feature
		//$to = 'name@mail.com'; // your email address if you want new posts in guestbook mailed to you.
		//$subject = $newsauthor.' has written a new post in your guestbook';
		//mail($to, $subject, $newsinput);

		// create file in messages folder
		$h = fopen($newsfile, 'w+');
		fwrite($h, html_entity_decode($newsinput));
		fclose($h);
		
		
		
		?>
			<div class="alert alert-success"><i class="fa fa-check"></i>&nbsp;&nbsp;Newsmessage is posted!</div>
		<?php
	}
}
	
/* DELETE MESSAGE */
if(isset($_POST['delete_file'])) {	
			
	$filename = 'messages/'.$_POST['delete_file'].'.txt';
	
	// delete images if were uploaded	
	$txtmsg = file_get_contents($filename);
	
	preg_match_all( '@src="([^"]+)"@' , $txtmsg, $match );
	$images = array_pop($match);

	foreach ($images as $image) {
		unlink($image); // delete images
	}

	// delete article
	if(file_exists($filename)) {
 
		unlink($filename);						 
		echo '<div class="alert alert-success"><i class="fa fa-check"></i>&nbsp;&nbsp;<b>'.$_POST['news_title'].'</b> deleted!</div>';						 
	}
	else {
		echo '<div class="alert alert-danger"><i class="fa fa-times"></i>&nbsp;&nbsp;<b>'.$_POST['news_title'].'</b> does not exist!</div>';
	}
}

/* EDIT MESSAGE*/
if (isset($_POST['edit_file'])) {
	
	$edit_input = $_POST['file_id'].PHP_EOL;
	$edit_input .= $_POST['news_title'].PHP_EOL;
	$edit_input .= $_POST['news_date'].PHP_EOL;
	$edit_input .= $_POST['news_author'].PHP_EOL;
	$edit_input .= $_POST['news_message'].PHP_EOL;
	
	$messagefile = 'messages/';
	$messagefile .= $_POST['file_id'] . '.txt'; //name of the file is the same as unique_id

	 echo '<div class="alert alert-success"><i class="fa fa-check"></i>&nbsp;&nbsp;<b>'.$_POST['news_title'].'</b> updated!</div>';
	
	$h = fopen($messagefile, 'w+');
	fwrite($h, $edit_input);
	fclose($h);			
}
/* END EDIT MESSAGE */


// read files in messages folder
$dir = 'messages/';
if ($dh = opendir($dir)) {
	while(($file = readdir($dh))!== false){
		if ($file != "." && $file != "..") { // This line strips out . & ..
			$newslist[] = $file;
			
			
		}
		
	}
}

closedir($dh);



if (isset($newslist)) {
	// sort array
	rsort($newslist);

	// PAGINATION code by Crayon Violent, PHP Freaks - http://www.phpfreaks.com/tutorial/basic-pagination
	$numrows = count($newslist);
	$totalpages = ceil($numrows/$articlesperpage);

	// get the current page or set a default
	if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
		// cast var as int
		$currentpage = (int) $_GET['currentpage'];
	} else {
		// default page num
		$currentpage = 1;
	} // end if

	// if current page is greater than total pages...
	if ($currentpage > $totalpages) {
		// set current page to last page
		$currentpage = $totalpages;
	} // end if

	// if current page is less than first page...
	if ($currentpage < 1) {
		// set current page to first page
		$currentpage = 1;
	} // end if

	// the offset of the list, based on current page 
	$offset = ($currentpage - 1) * $articlesperpage;


	$page = $_GET['page'];
	$pagemsgs = array_slice($newslist, $offset, $articlesperpage);

	if(!isset($page) && !isset($news_search)) { // do not list all news articles when viewing single page or search results
/*** ALL MESSSAGES ***/
		foreach($pagemsgs as $file){
						
			// open and prepare message
			$newsmsg = 'messages/'.$file;						
					
			// get data out of txt file		
			$lines = file($newsmsg, FILE_IGNORE_NEW_LINES);// filedata into an array
			
			$file_id = $lines[0]; // file id
			$news_title = $lines[1]; //  news title
			$news_date = $lines[2]; // news date
			$news_author = $lines[3]; //  author name
			$news_message = $lines[4]; // news message
			$news_message_reduced = substr($lines[4], 0, $max_chars); // reduce characters; max_chars editable in settings
							
			fclose($fh);
			?>
			
			<!-- CONTENT ARTICLE -->				
			<div class="news-title page-header"><h3><?php echo $news_title; ?></h3></div>									
			<div class="news-message">			
			<?php 
				echo $news_message_reduced;
				if(strlen($news_message) > $max_chars) {
				?>
				...&nbsp;<a href="admin.php?page=<?php echo $file_id; ?>">Read more</a>
				<?php					
				}				
			?>						
			</div>	
			<div class="news-date pull-left"><i class="fas fa-calendar-alt"></i><?php echo $news_date; ?></div>
			<div class="news-author pull-left"><i class="fas fa-user"></i><?php echo $news_author; ?></div>
			
			<!-- Delete button-->
			<form action="admin.php" method="POST" role="form">	
				<input type="hidden" class="form-control" name="delete_file" value="<?php echo $file_id; ?>" />	
				<input type="hidden" class="form-control" name="news_title" value="<?php echo $news_title; ?>" />	
				<input type="hidden" class="form-control" name="news_author" value="<?php echo $news_author; ?>" />							
				<button class="icon-button pull-right" type="submit" name="submit"><i class="fas fa-trash-alt alert-danger"></i></button>				
			</form>
			<!-- Button for edit; open modal -->
			<button type="button" class="icon-button pull-right" data-toggle="modal" data-target="#<?php echo $file_id; ?>"><i class="fas fa-edit"></i></button>
			
			<?php
			include('includes/modal.php'); // include modal dialog for editting
		} // end foreach

	}
	elseif(!isset($news_search)) { // ignore when request is news_search
/*** SINGLE MESSAGE ***/
		
		// open and prepare message
			$newsmsg = 'messages/'.$page.'.txt';			
								
			// get data out of txt file		
			$lines = file($newsmsg, FILE_IGNORE_NEW_LINES);// filedata into an array
			
			$file_id = $lines[0]; // file id
			$news_title = $lines[1]; //  news title
			$news_date = $lines[2]; // news date
			$news_author = $lines[3]; //  author name
			$news_message = $lines[4]; // news message
			
			fclose($fh);
			?>
			<a href="admin.php">Back to all articles</a>
			<br /><br />
			
			
			<!-- CONTENT ARTICLE -->			
			<div class="news-title page-header"><h3><?php echo $news_title; ?></h3></div>
			<div class="news-message"><?php echo $news_message; ?></div>			
			<div class="news-date pull-left"><i class="fas fa-calendar-alt"></i><?php echo $news_date; ?></div>
			<div class="news-author pull-left"><i class="fas fa-user"></i><?php echo $news_author; ?></div>
			
			<!-- Delete button -->
			<form action="admin.php" method="POST" role="form">	
				<input type="hidden" class="form-control" name="delete_file" value="<?php echo $file_id; ?>" />	
				<input type="hidden" class="form-control" name="news_title" value="<?php echo $news_title; ?>" />	
				<input type="hidden" class="form-control" name="news_author" value="<?php echo $news_author; ?>" />							
				<button class="icon-button pull-right" type="submit" name="submit"><i class="fas fa-trash-alt alert-danger"></i></button>				
			</form>
			<!-- Button for edit; open modal -->
			<button type="button" class="icon-button pull-right" data-toggle="modal" data-target="#<?php echo $file_id; ?>"><i class="fas fa-edit"></i></button>
			
			<?php
			include('includes/modal.php'); // include modal dialog for editting
		
	}


	/******  build the pagination links ******/
	// Do not show navigation when search request of single page request
	if($_GET['news_search'] == NULL && $_GET['page'] == NULL) {
		?>

		<ul class="pagination">
		<?php

		// if not on page 1, show back links
		if ($currentpage > 1) {
			// show << link to go back to page 1
			echo "<li class=\"page-item\"><a class=\"page-link\" href='{$_SERVER['PHP_SELF']}?currentpage=1'><i class=\"fas fa-step-backward\"></i></a></li> ";
			// get previous page num
			$prevpage = $currentpage - 1;
			// show < link to go back to 1 page
			echo "<li class=\"page-item\"><a class=\"page-link\" href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><i class=\"fas fa-backward\"></i></a></li> ";
		} // end if

		// loop to show links to pagination_range of pages around current page
		for($x = ($currentpage - $pagination_range); $x < (($currentpage + $pagination_range) + 1); $x++){
			// if it's a valid page number...
			if (($x > 0) && ($x <= $totalpages)) {
				// if we're on current page...
				if ($x == $currentpage) {
					// 'highlight' it but don't make a link
					echo "<li class=\"page-item current\"><a class=\"current\">$x</a></li>";
					// if not current page...
				} else {
					// make it a link
					echo "<li class=\"page-item\"><a class=\"page-link\" href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a></li> ";
				} // end else
			} // end if 
		} // end for

		// if not on last page, show forward and last page links        
		if ($currentpage != $totalpages) {
			// get next page
			$nextpage = $currentpage + 1;
			// echo forward link for next page 
			echo "<li class=\"page-item\"><a class=\"page-link\" href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'><i class=\"fas fa-forward\"></i></a></li>";
			// echo forward link for lastpage
			echo "<li class=\"page-item\"><a class=\"page-link\" href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'><i class=\"fas fa-step-forward\"></i></a></li>";
		} // end if

		?>
		</ul>
		<?php
	}

	/****** end build pagination links ******/
} 
else {
	echo '<div>No messages yet</div>';
}
?>
