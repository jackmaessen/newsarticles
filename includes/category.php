<?php

// Filter category
$filter_category = $_GET['filter_category']; // get string from url for filter
if (isset($filter_category)) {
	
	$filterthis = strtolower($filter_category);
	$filtercategorymatches = array();
	
	$files = glob("messages/*.txt"); // Specify the file directory by extension (.txt)

	foreach($files as $file) {// Loop the files in the directory	
			
		$handle = @fopen($file, "r");
								
		if ($handle) {
			
			$lines = file($file); //file in to an array
			$buffer = $lines[1];
			
			if(strpos(strtolower($buffer), $filterthis) !== FALSE) {// strtolower; category word not case sensitive	
					
					
					$filtercategorymatches[] = $file; // The filename of the match, eg: messages/1.txt
					 										
			}
			fclose($handle);
		}
	}


	/////////////////////////////////

	// if found matches for category
	if (isset($filtercategorymatches)) {
		// sort array 
		rsort($filtercategorymatches); 

		// PAGINATION code by Crayon Violent, PHP Freaks - http://www.phpfreaks.com/tutorial/basic-pagination
		$numrows = count($filtercategorymatches);
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

		$category_matches = array_slice($filtercategorymatches, $offset, $articlesperpage);
		
	///////////////////////////////


		
		//show results:
		if($category_matches != NULL) { // found a match 
		
			$total_matches = count($filtercategorymatches); // count the number of matches; need for pagination
			$totalpages_match = ceil($total_matches/$articlesperpage);
						
			?>
			<a class="float-left" href="<?php echo $_SERVER["PHP_SELF"]; ?>">Back to all articles</a>
			<br />
			<div class="page-header">
				<h3 class="float-left">Filter results for category: <b class="news-category"><?php echo $filter_category; ?></b></h3>
				<span class="float-right">Total: <b><?php echo $total_matches; ?></b></span>
			</div>				
			<div class="clearfix"></div>
			
			<?php
				
			foreach($category_matches as $match) { 
									
				$lines = file($match, FILE_IGNORE_NEW_LINES); // filedata into an array
				
				$file_id = $lines[0]; // file id
				$news_category = $lines[1]; //  news category
				$news_title = $lines[2]; //  news title
				$news_date = $lines[3]; // news date
				$news_author = $lines[4]; //  author name
				$news_message = $lines[5]; // news message
				$news_message_reduced = substr($lines[5], 0, $max_chars); // reduce characters; max_chars editable in settings.php
				$news_hit_counter = $lines[6];	
				
				$readmore_link = $_SERVER["PHP_SELF"]."?page=".$file_id;
			
				?>
				<!-- Styling output -->
				<div class="card bg-light">
					<div class="card-body">
						<div class="news-title border-bottom"><h3><a href="<?php $_SERVER["PHP_SELF"]; ?>?page=<?php echo $file_id; ?>"><?php echo $news_title; ?></a></h3></div>
						<div class="news-message p-3">			
						<?php 
							echo $news_message_reduced;
							if(strlen($news_message) > $max_chars) {
							?>
							...&nbsp;<a href="<?php echo $readmore_link; ?>">Read more</a>
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
						<?php
						// admin only
						if($admin) {
						?>
						<!-- Delete button -->
						<form action="admin.php" method="POST" role="form">	
							<input type="hidden" class="form-control" name="delete_file" value="<?php echo $file_id; ?>" />	
							<input type="hidden" class="form-control" name="news_title" value="<?php echo $news_title; ?>" />						
							<button class="btn btn-danger float-right delete" type="submit" name="submit">Delete</button>				
						</form>
						<!-- Button for edit; open modal -->
						<button type="button" class="btn btn-secondary float-right edit" data-toggle="modal" data-target="#<?php echo $file_id; ?>">Edit</button>					
												
						<div class="clearfix"></div>
						<?php } ?>					
					</div>
				</div>
				<?php
				// admin only
				if($admin) {
					include 'includes/modal.php';
				}
			} // end foreach
			
			// pagination 			
			include 'includes/pagination.php';
		}
		else {
			?>
				<a class="float-left"href="<?php echo $_SERVER["PHP_SELF"]; ?>">Back to all articles</a>
				<br /><br />
				<div class="noresults">No articles found in category:&nbsp;<b class="news-category"> <?php echo $filter_category; ?></b></div>
				<div class="clearfix"></div>
			<?php
		}
	}
}			
?>