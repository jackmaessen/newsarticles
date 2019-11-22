<?php
// Search articles
$news_search = $_GET['news_search']; // get string from url for search
if (isset($news_search)) {
	$searchthis = strtolower($news_search);
	$matches = array();

	$files = glob("messages/*.txt"); // Specify the file directory by extension (.txt)

	foreach($files as $file) // Loop the files in the directory
	{
			
		$handle = @fopen($file, "r");
		if ($handle)
		{
			while (!feof($handle))
			{
				$buffer = fgets($handle);
				if(strpos(strtolower($buffer), $searchthis) !== FALSE) // strtolower; search word not case sensitive
					$matches[] = $file; // The filename of the match, eg: messages/1.txt
					
			}
			fclose($handle);
		}
	}

	//show results:
	if($matches != NULL) { // found a match 
		?>
		<a class="pull-right"href="<?php echo $_SERVER["PHP_SELF"]; ?>">Back to all articles</a>
		<div class="results">Search results for: <b><?php echo $news_search; ?></b></div>
		<br />
		<?php
				
		foreach(array_unique($matches) as $match) { // array_unique; if 2 or more matches in the same file, show file only once 
									
			$lines = file($match, FILE_IGNORE_NEW_LINES); // filedata into an array
			
			$file_id = $lines[0]; // file id
			$news_title = $lines[1]; //  news title
			$news_date = $lines[2]; // news date
			$news_author = $lines[3]; //  author name
			$news_message = $lines[4]; // news message
			$news_message_reduced = substr($lines[4], 0, $max_chars);
			
			$readmore_link = $_SERVER["PHP_SELF"]."?page=".$file_id;
			
			?>
			<!-- Styling output -->				
			<div class="news-title page-header"><h3><?php echo $news_title; ?></h3></div>
			<div class="news-message">			
			<?php 
				echo $news_message_reduced;
				if(strlen($news_message) > $max_chars) {
				?>
				...&nbsp;<a href="<?php echo $readmore_link; ?>">Read more</a>
				<?php					
				}				
			?>						
			</div>	
			<div class="news-date pull-left"><i class="fas fa-calendar-alt"></i><?php echo $news_date; ?></div>
			<div class="news-author pull-left"><i class="fas fa-user"></i><?php echo $news_author; ?></div>
									
			<div class="clearfix"></div>
			<?php
			
		}
	}
	else {
		?>
			<a class="pull-left"href="<?php echo $_SERVER["PHP_SELF"]; ?>">Back to all articles</a>
			<br /><br />
			<div class="noresults">No matches found...</div>
			<div class="clearfix"></div>
		<?php
		
	}
}
?>
