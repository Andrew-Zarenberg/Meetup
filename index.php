<?php include("include.php"); ?>

<html>
	<head>
	
		<title>Meetup</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		
		<?php
			// If just created account
			if(isset($_GET["newuser"])){
				$success[] = "Account successfully created!";
			} else if(isset($_GET["loggedin"])){
				$success[] = "Successfully logged in!";
			} else if(isset($_GET["loggedout"])){
				$success[] = "Successfully logged out!";
			} else if(isset($_GET["attemptregister"])){
				$error[] = "You are already logged in - Log out to register a new account";
			} else if(isset($_GET["attemptlogin"])){
				$error[] = "You are already logged in - Log out to log in";
			}
			
			
			// If user is signed in
			if(isset($_SESSION["username"])){
				// Welcome message
				echo '<div id="welcome">Welcome <strong>'.$_SESSION["username"].'</strong></div>';
			}
		?>
		
		<?php print_errors($error, $success); ?>
			
			
		
		<?php include("body_footer.php"); ?>
	</body>
</html>