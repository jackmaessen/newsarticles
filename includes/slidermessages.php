<?php
include('settings.php');
	
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
	
	$pagemsgs = array_slice($newslist, $offset, $articlesperpage);
	
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
			<!-- Show all articles in the slider-->
			<div class="col-md-3 col-lg-3">
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
			</div>							
			<?php	
			
		} // end foreach		
	
} 
else {
	echo '<div>No messages yet</div>';
}
?>