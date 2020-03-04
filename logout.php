<?php 
	session_start();
	unset($_SESSION['news_login']);
	header("Location: admin.php?logout=true");
?>
