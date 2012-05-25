<?php
session_start();
include("config.php");
include("functions.php");
$conn = connect();
$page = $_GET['p'];
$msg = $_GET['msg'];
$do = $_GET['do'];

//lets check if login is right
if($do == "tryLogin"){
	$try = checkPass(pass($_POST['password']));
	if($try != false){
		login($try);
		$msg = "loginSuccess";
	}else{
		$msg = "loginFail";
	}
}
//logout
if($do == "logout"){
	logout();
	$msg = "logoutSuccess";
}

//some error messages
$messages["loginFail"] 		= 	array("bad" => 1, "message" => "Ooops, wrong password!");
$messages["loginSuccess"] 	= 	array("bad" => 0, "message" => "You have been logged in!");
$messages["logoutSuccess"] 	= 	array("bad" => 0, "message" => "You have been logged out!");
$messages["404"] 			= 	array("bad" => 1, "message" => "Ooops, did not get that right!");

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>MPhoto</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Simple photo gallery">
		<meta name="author" content="Marko Kaartinen">

		<!-- Le styles -->
		<link href="./css/bootstrap.min.css" rel="stylesheet">
		<style>
			body {
				padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
			}
		</style>
		<link href="./css/bootstrap-responsive.min.css" rel="stylesheet">
		<link href="./css/style.css" rel="stylesheet">

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le fav and touch icons -->
		<link rel="shortcut icon" href="../assets/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="index.php">MPhoto</a>
					<div class="nav-collapse">
						<ul class="nav">
							<li<?php if($page == ""){ echo' class="active" '; } ?>><a href="index.php">Home</a></li>
							<?php if(isAdmin()){ ?><li<?php if($page == "admin"){ echo' class="active" '; } ?>><a href="index.php?p=admin">Admin</a></li><?php } ?>
							<?php if(checkLogin()){ ?><li><a href="index.php?do=logout">Logout</a></li><?php } ?>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>
	
		<div class="container" id="content">
			<?php
			//show error message if there is one
			if($msg != ""){
				if($messages[$msg]['bad'] == 1){
					$errorStyle = "alert alert-error";
				}else{
					$errorStyle = "alert alert-success";
				}
				echo '<div class="'.$errorStyle.'"><a class="close" data-dismiss="alert" href="#">×</a>'.$messages[$msg]['message'].'</div>';
			}
			
			//dynamic page system
			if($page == ""){
				include("./pages/front.php");
			}else{
				if(file_exists("./pages/$page.php")){
					include("./pages/$page.php");
				}else{
					include("./pages/front.php?msg=404");
				}
			}
			?>
			
			<hr />
			
			<footer>
				<p>&copy; Marko Kaartinen - Powered by Bootstrap</p>
			</footer>
		</div> <!-- /container -->
				
		<!-- Le javascript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="./js/jquery.js"></script>
		<script src="./js/bootstrap.min.js"></script>
	</body>
</html>
