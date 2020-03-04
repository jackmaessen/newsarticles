<?php
include 'settings.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$error = "";
	if(isset($_POST['username'],$_POST['password'])){
		$user = array(
						"username" => $admin_name,
						"password"=> $admin_passw			
				);
		$username = $_POST['username'];
		$pass = $_POST['password'];
		if($username == $user['username'] && $pass == $user['password']){
			session_start();
			$_SESSION['news_login'] = $username;
			header("Location: admin.php?login=true");
		}
		else {
			$error = '<div class="alert alert-danger">Incorrect login data</div>';
			
		}
		
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<title>Mosaregio-login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	
</head>
<body>
<br /><br />
	<div class="container">
		<div class="row">
		
			<div class="col-md-4 col-md-offset-4">				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Login Admin</h3>
					</div>
					<div class="panel-body">
						<?php echo $error; ?>
						<form  method="post" action="login.php">
							<fieldset>
								<div class="form-group">
									<input class="form-control" placeholder="Username" name="username" type="text">
								</div>
								<div class="form-group">
									<input class="form-control" placeholder="Password" name="password" type="password" value="">
								</div>
									<input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
							</fieldset>
						</form>
					</div>
				</div>								
			</div>
			
		</div>
	</div>
	
</body>
</html>
