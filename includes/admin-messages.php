<?php
include 'settings.php';

if(basename($_SERVER['PHP_SELF']) == 'admin.php') {
	$admin = true;
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

// name block
if (isset($_POST['name'])) {
	$safenewsinput = htmlentities($_POST['name']);
	if($safenewsinput == NULL){
		$newsauthor = 'Anonymous';								
	} else {
		$newsauthor = $safenewsinput;
	}
}

// category block
if (isset($_POST['category'])) {
	$safenewsinput = htmlentities($_POST['category']);										
	$newscategory = $safenewsinput;	
}


// message block
if (isset($_POST['message'])) {
	$safenewsinput = htmlentities($_POST['message']);
	$newsmessage = $safenewsinput;
	if ($newsmessage == NULL){				
		?>
			<div class="alert alert-danger"><i class="fa fa-times"></i>&nbsp;&nbsp;Message can't be empty</div>
		<?php
		
	} else {
		// set name and content of message file; each message has unique id
		$unique_id = 'id_'.date('YmdHis');
		
		// put content in .txt file with linebreaks; unique_id first
		$newsinput = $unique_id.PHP_EOL;
		$newsinput .= $newscategory.PHP_EOL;
		$newsinput .= $newstitle.PHP_EOL;						
		$newsinput .= $dateformat.PHP_EOL; // determined in settings
		$newsinput .= $newsauthor.PHP_EOL;
		$newsinput .= $newsmessage.PHP_EOL;
								
		$newsfile = 'messages/';
		$newsfile .= $unique_id . '.txt'; //name of the file is the same as unique_id

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
	
	// find src attr
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
	$edit_input .= $_POST['news_category'].PHP_EOL;
	$edit_input .= $_POST['news_title'].PHP_EOL;
	$edit_input .= $_POST['news_date'].PHP_EOL;
	$edit_input .= $_POST['news_author'].PHP_EOL;
	$edit_input .= $_POST['news_message'].PHP_EOL;
	$edit_input .= $_POST['news_counter'].PHP_EOL;
	
	$newsfile = 'messages/'.$_POST['file_id'].'.txt'; //name of the file is the same as unique_id

	echo '<div class="alert alert-success"><i class="fa fa-check"></i>&nbsp;&nbsp;<b>'.$_POST['news_title'].'</b> updated!</div>';
	
	$h = fopen($newsfile, 'w+');
	fwrite($h, $edit_input);
	fclose($h);			
}



// read files in messages folder
$dir = 'messages/';
if ($dh = opendir($dir)) {
	while(($file = readdir($dh))!== false){
		if ($file != "." && $file != "..") { // This line strips out . & ..
			$newslist[] = $dir.$file;
						
		}		
	}
}

closedir($dh);

// include category and search 
include 'includes/search.php';
include 'includes/category.php';

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
}


if( !isset($page) && !isset($news_search) && !isset($filter_category) ) { // do not list all news articles when viewing single page, filter category or search results
/*** ALL MESSSAGES ***/

	$total_articles = count($newslist); // count the number of articles
		
	?>
	<div class="page-header">
		<h3 class="float-left">Newsmessages</h3>
		<span class="float-right">Total: <b><?php echo $total_articles; ?></b></span>
	</div>		
	<div class="clearfix"></div>
	<?php 
	
	foreach($pagemsgs as $file){
				
		// get data out of txt file		
		$lines = file($file, FILE_IGNORE_NEW_LINES);// filedata into an array
		
		$file_id = $lines[0]; // file id
		$news_category = $lines[1]; //  news category
		$news_title = $lines[2]; //  news title
		$news_date = $lines[3]; // news date
		$news_author = $lines[4]; //  author name
		$news_message = $lines[5]; // news message
		$news_message_reduced = substr($lines[5], 0, $max_chars); // reduce characters; max_chars editable in settings.php
		$news_hit_counter = $lines[6];	
						
		fclose($fh);
		
		?>
		
		<!-- show all articles -->
		<div class="card bg-light">
			
			<div class="card-body">			
				<div class="news-title border-bottom">
					<h3><a href="admin.php?page=<?php echo $file_id; ?>"><?php echo $news_title; ?></a></h3>							
				</div>						
				
				
				<div class="news-message p-3">			
				<?php 
					echo $news_message_reduced;
					if(strlen($news_message) > $max_chars) {
					?>
					...&nbsp;<a href="admin.php?page=<?php echo $file_id; ?>">Read more</a>
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
				<!-- Delete button-->
				<form action="admin.php" method="POST" role="form">	
					<input type="hidden" class="form-control" name="delete_file" value="<?php echo $file_id; ?>" />	
					<input type="hidden" class="form-control" name="news_title" value="<?php echo $news_title; ?>" />						
					<button class="btn btn-danger float-right delete" type="submit" name="submit">Delete</button>				
				</form>
				<!-- Button for edit; open modal -->
				<button type="button" class="btn btn-secondary float-right edit" data-toggle="modal" data-target="#<?php echo $file_id; ?>">Edit</button>
				
			</div>
		</div>
		
		<?php
		include('includes/modal.php'); // include modal dialog for editting
	} // end foreach
	
	// pagination
	include 'includes/pagination.php';
}
elseif( !isset($news_search) && !isset($filter_category) ) { // ignore when request is news_search or filter_category
/*** SINGLE MESSAGE ***/
	
	// open and prepare message
	$file = 'messages/'.$page.'.txt';			
						
	// get data out of txt file		
	$lines = file($file, FILE_IGNORE_NEW_LINES); // filedata into an array
	
	$file_id = $lines[0]; // file id
	$news_category = $lines[1]; //  news category
	$news_title = $lines[2]; //  news title
	$news_date = $lines[3]; // news date
	$news_author = $lines[4]; //  author name
	$news_message = $lines[5]; // news message
	$news_hit_counter = $lines[6];	
	
	fclose($fh);
	?>
	<a class="float-left" href="admin.php">Back to all articles</a>
	<br />
			
	<!-- show single article-->
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
			<!-- Delete button -->
			<form action="admin.php" method="POST" role="form">	
				<input type="hidden" class="form-control" name="delete_file" value="<?php echo $file_id; ?>" />	
				<input type="hidden" class="form-control" name="news_title" value="<?php echo $news_title; ?>" />						
				<button class="btn btn-danger float-right delete" type="submit" name="submit">Delete</button>				
			</form>
			<!-- Button for edit; open modal -->
			<button type="button" class="btn btn-secondary float-right edit" data-toggle="modal" data-target="#<?php echo $file_id; ?>">Edit</button>
			
		</div>
	</div>
	<?php
	include('includes/modal.php'); // include modal dialog for editting
	
}





?>