<?php include("include.php"); ?>

<html>
	<head>
	
		<title>Meetup</title>
		<!-- Include header -->
		
	</head>
	<body>
		<!-- Include body header -->
		
		<?php
			// If just created account
			if(isset($_GET["newuser"])){
				echo "<div>Account successfully created!</div>";
			} else if(isset($_GET["loggedin"])){
				echo "<div>Successfully logged in</div>";
			} else if(isset($_GET["loggedout"])){
				echo "<div>Successfully logged out</div>";
			}
			
			
			// If user is signed in
			if(isset($_SESSION["username"])){
				// Welcome message
				echo '<div id="welcome">Welcome <strong>'.$_SESSION["username"].'</strong></div>';
			}
		?>
			
			
		
		<!-- Include body footer -->
	</body>
</html>