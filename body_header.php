<!-- Start Body Header -->

<div id="top">
	
	<div style="float:left;">
		<a href="index.php">Home Page</a>
	</div>

	<?php
		if(isset($_SESSION["username"])){
			echo "Logged in as <strong>".$_SESSION["username"]."</strong>";
			?>
			<div style="float:right">
				<a href="groups.php">List of Groups</a> | 
				<a href="events.php">List of Events</a> |
				<a href="user.php">My Profile</a> | 
				<a href="logout.php">Log Out</a>
			</div>
			<?php
		} else {
			echo "You are not logged in";
			echo '<div style="float:right"><a href="groups.php">List of Groups</a> | <a href="events.php">List of Events</a> | <a href="register.php">Register</a> | <a href="login.php">Log In</a></div>';
		}
	?>

</div>

<div id="main">

<!-- End Body Header -->