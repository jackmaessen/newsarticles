<?php

// login variables
$admin_name = 'admin';
$admin_passw = 'password1';
// Set variables below to your own needs 
$all_categories = array( // edit and add name of the categories to your own needs 
	'Friends',
	'Family',
	'Collegas',
	'Club',
	'Other1',
	'Other2',
	// add as much as you want
	'Offside' // Offside must be the last one!
	
);

date_default_timezone_set('Europe/Amsterdam'); // timezone
$dateformat = date('d M Y H:i'); // day Month Year Hours Seconds; see https://www.php.net/manual/en/function.date.php
$articlesperpage = 5; // number of articles per page to show; 
$articles_slider = 10; // number of articles to show in slider; 
$max_chars = 600; // max characters to show in articles list; then show "readmore" link
$pagination_range = 2; // pagination_range of num links to show
$maxtitls = 5; // max "last messages" in sidecolumn to articles

?>