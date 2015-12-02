<!-- Start Body Header -->

<div id="top">
	
	<div style="float:left;">
		<a href="index.php">Home Page</a>
	</div>

	<?php
		if(isset($_SESSION["username"])){
			echo "Logged in as <strong>".$_SESSION["username"]."</strong>";
		} else {
			echo "You are not logged in";
		}
	?>
	
	<div style="float:right">
		<a href="groups.php">List of Groups</a> | 
		<a href="events.php">List of Events</a> |
		<a href="interests.php">List of Interests</a> | 
		<a href="user.php">My Profile</a> | 
		<a href="logout.php">Log Out</a>
	</div>
	
</div>

<div id="main">

<!-- End Body Header -->