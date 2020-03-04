<?php
include('settings.php');
	
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
	
	$pagemsgs = array_slice($filtered_newslist, $offset, $articles_slider); // $articles_slider determined in settings
	
		foreach($pagemsgs as $file){
					
			// open and prepare message
			// get data out of txt file		
			$lines = file($file, FILE_IGNORE_NEW_LINES); // filedata into an array
			
			$file_id = $lines[0]; // file id
			$news_title = $lines[1]; //  news category
			$news_title = $lines[2]; //  news title
			$news_date = $lines[3]; // news date
			$news_author = $lines[4]; //  author name
			$news_message = $lines[5]; // news message
			$news_message_reduced = substr($lines[5], 0, $max_chars); // reduce characters; max_chars editable in settings.php
			$news_hit_counter = $lines[6];
							
			fclose($fh);
				
			?>

			<div class="col-md-3 col-lg-3">
				<div class="card bg-light">					
					<div class="card-body">
						<div class="news-title border-bottom"><h3><a href="index.php?page=<?php echo $file_id; ?>"><?php echo $news_title; ?></a></h3></div>
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
					</div>
					<div class="card-footer">					
						<div class="news-date float-left"><i class="fas fa-calendar-alt"></i><?php echo $news_date; ?></div>
						<div class="news-author float-left"><i class="fas fa-user"></i><?php echo $news_author; ?></div>
						<div class="news-counter float-left"><i class="fas fa-eye"></i><?php echo $news_hit_counter; ?></div>
												
						<div class="clearfix"></div>
					</div>
				</div>		
			</div>							
			<?php	
			
		} // end foreach		
	
} 
else {
	echo '<div>No messages yet</div>';
}
?>