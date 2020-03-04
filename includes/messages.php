<?php
include 'settings.php';

if(basename($_SERVER['PHP_SELF']) == 'admin.php') {
	$admin = true;
}

include 'includes/search.php';
include 'includes/category.php';

/* COUNT ANCHOR HITS */
if(isset($_GET['page'])) {
		
	$countfile = 'messages/'.$_GET['page'].'.txt';
	// get data out of txt file		
	$lines = file($countfile, FILE_IGNORE_NEW_LINES);// filedata into an array

	$file_id = $lines[0]; // file id
	$news_category = $lines[1]; //  news category
	$news_title = $lines[2]; //  news title
	$news_date = $lines[3]; // news date
	$news_author = $lines[4]; //  author name
	$news_message = $lines[5]; // news message
	$news_message_reduced = substr($lines[5], 0, $max_chars); // reduce characters; max_chars editable in settings
	$news_hit_counter = $lines[6];	
			
	fclose($fh);

	// put content back in .txt file 
	$newsinput = $file_id.PHP_EOL;
	$newsinput .= $news_category.PHP_EOL;
	$newsinput .= $news_title.PHP_EOL;						
	$newsinput .= $news_date.PHP_EOL; // determined in settings
	$newsinput .= $news_author.PHP_EOL;
	$newsinput .= $news_message.PHP_EOL;
	$newsinput .= $news_hit_counter + 1 .PHP_EOL;
							
	// write back to the file
	$h = fopen($countfile, 'w+');
	fwrite($h, html_entity_decode($newsinput));
	fclose($h);
						
}
	

// read all files in messages folder
$dir = 'messages/';
if ($dh = opendir($dir)) {
	while(($file = readdir($dh))!== false){
		if ($file != "." && $file != "..") { // This line strips out . & ..
			$newslist[] = $dir.$file;						
		}		
	}
}
closedir($dh);


/////////////////////////////////////////////////////////////////////////
// Strip file(s) with category Offside	
$strip_cat = strtolower('Offside');
$offside_array = array();
	
$files = glob("messages/*.txt"); // Specify the file directory by extension (.txt)

foreach($files as $file) { // Loop through the files in the directory	
		
	$handle = @fopen($file, "r");
							
	if ($handle) {
		
		$lines = file($file); //file into an array
		
		$buffer = $lines[1];
	
		if(strpos(strtolower($buffer), $strip_cat) !== FALSE) { // strtolower; search word not case sensitive	
								
				$offside_array[] = $file; // The filename of the match
								
		}
		fclose($handle);
	}
}



// compare the arrays and strip out the files which contain cat Offside
$filtered_newslist = array_diff($newslist, $offside_array);


if (isset($filtered_newslist)) {
	// sort array
	rsort($filtered_newslist);
	// PAGINATION code by Crayon Violent, PHP Freaks - http://www.phpfreaks.com/tutorial/basic-pagination
	$numrows = count($filtered_newslist);
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
	$pagemsgs = array_slice($filtered_newslist, $offset, $articlesperpage);

/*** ALL MESSAGES **/
	if( !isset($page) && !isset($news_search) && !isset($filter_category) ) { // do not list all news articles when viewing single page, filter category or search results
		
		$total_articles = count($newslist) - count($offside_array); // count the number of articles; exclude Offside cat
		
		?>
		<div class="page-header">
			<h3 class="float-left">Newsmessages</h3>
			<span class="float-right">Total: <b><?php echo $total_articles; ?></b></span>
		</div>			
		<div class="clearfix"></div>
		<?php
		
		foreach($pagemsgs as $file) {
					
			// open and prepare message
			//$newsmsg = 'messages/'.$file;
										
			// get data out of txt file		
			$lines = file($file, FILE_IGNORE_NEW_LINES);// filedata into an array
			
			$file_id = $lines[0]; // file id
			$news_category = $lines[1]; //  news category
			$news_title = $lines[2]; //  news title
			$news_date = $lines[3]; // news date
			$news_author = $lines[4]; //  author name
			$news_message = $lines[5]; // news message
			$news_message_reduced = substr($lines[5], 0, $max_chars); // reduce characters; max_chars editable in settings
			$news_hit_counter = $lines[6];				
			fclose($fh);
				
			?>
			<!-- Show all articles-->
			<div class="card bg-light">
				<div class="card-body">
					<div class="news-title border-bottom">
						<h3><a href="index.php?page=<?php echo $file_id; ?>"><?php echo $news_title; ?></a></h3>
					</div>
					<div class="news-message p-3">			
					<?php 
						echo $news_message_reduced;
						if(strlen($news_message) > $max_chars) {
						?>
						...&nbsp;<a href="index.php?page=<?php echo $file_id; ?>">Read more</a>
						<?php					
						}				
					?>						
					</div>	
				</div>
				<div class="card-footer">
					<div class="news-date float-left"><i class="fas fa-calendar-alt"></i><?php echo $news_date; ?></div>
					<div class="news-author float-left"><i class="fas fa-user"></i><?php echo $news_author; ?></div>
					<div class="news-counter float-left"><i class="fas fa-eye"></i><?php echo $news_hit_counter; ?></div>
					<div class="news-category float-right"><i class="fas fa-tag"></i><?php echo $news_category; ?></div>
					
											
					<div class="clearfix"></div>
				</div>
			</div>							
			<?php	
			
		} // end foreach
		
		// pagination
		include 'includes/pagination.php';

	}
	elseif( !isset($news_search) && !isset($filter_category) ) { // ignore when request is news_search or filter_category
/*** SINGLE MESSAGE ***/
			
		// open and prepare message
		$newsmsg = 'messages/'.$page.'.txt';
						
		// get data out of txt file		
		$lines = file($newsmsg, FILE_IGNORE_NEW_LINES);// filedata into an array
		
		$file_id = $lines[0]; // file id
		$news_category = $lines[1]; //  news category
		$news_title = $lines[2]; //  news title
		$news_date = $lines[3]; // news date
		$news_author = $lines[4]; //  author name
		$news_message = $lines[5]; // news message
		$news_hit_counter = $lines[6];	
			
		fclose($fh);
			
		?>
			
		<a class="float-left" href="index.php">Back to all articles</a>	
		<br />
		
		<!-- Show single article -->
		<div class="card bg-light">
			<div class="card-body">
				<div class="news-title border-bottom"><h3><?php echo $news_title; ?></h3></div>
				<div class="news-message p-3"><?php echo $news_message; ?></div>
			</div>
			<div class="card-footer">
				<div class="news-date float-left"><i class="fas fa-calendar-alt"></i><?php echo $news_date; ?></div>
				<div class="news-author float-left"><i class="fas fa-user"></i><?php echo $news_author; ?></div>
				<div class="news-counter float-left"><i class="fas fa-eye"></i><?php echo $news_hit_counter; ?></div>
				<div class="news-category float-right"><i class="fas fa-tag"></i><?php echo $news_category; ?></div>
							
				<div class="clearfix"></div>
			</div>
		</div>
		<?php
		
	}

	
} 
else {
	echo '<div>No messages yet</div>';
}