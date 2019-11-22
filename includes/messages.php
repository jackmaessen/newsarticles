<?php
include('settings.php');
include('includes/search.php');


	
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
	} 

	// if current page is greater than total pages...
	if ($currentpage > $totalpages) {
		// set current page to last page
		$currentpage = $totalpages;
	} 

	// if current page is less than first page...
	if ($currentpage < 1) {
		// set current page to first page
		$currentpage = 1;
	} 

	// the offset of the list, based on current page 
	$offset = ($currentpage - 1) * $articlesperpage;


	$page = $_GET['page']; // get string from url for single page
	$pagemsgs = array_slice($newslist, $offset, $articlesperpage);

/*** ALL MESSAGES **/
	if(!isset($page) && !isset($news_search)) { // do not list all news articles when viewing single page or search results

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
			<!-- Show all articles-->				
			<div class="news-title page-header"><h3><?php echo $news_title; ?></h3></div>
			<div class="news-message">			
			<?php 
				echo $news_message_reduced;
				if(strlen($news_message) > $max_chars) {
				?>
				...&nbsp;<a href="index.php?page=<?php echo $file_id; ?>">Read more</a>
				<?php					
				}				
			?>						
			</div>			
			<div class="news-date pull-left"><i class="fas fa-calendar-alt"></i><?php echo $news_date; ?></div>
			<div class="news-author pull-left"><i class="fas fa-user"></i><?php echo $news_author; ?></div>
									
			<div class="clearfix"></div>
										
			<?php	
			
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
		<!-- Show single article -->	
		<a href="index.php">Back to all articles</a>	
		<br /><br />
		<div class="news-title page-header"><h3><?php echo $news_title; ?></h3></div>
		<div class="news-message"><?php echo $news_message; ?></div>
		<div class="news-date pull-left"><i class="fas fa-calendar-alt"></i><?php echo $news_date; ?></div>
		<div class="news-author pull-left"><i class="fas fa-user"></i><?php echo $news_author; ?></div>
					
		<div class="clearfix"></div>						
		<?php
		
	}

	/******  build the pagination links ******/
	// Do not show navigation when search request or single page request
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
