<!-- Start Body Header -->

<div id="top">
	
	<table class="noborder" cellspacing="0">
		<tr>
		
	<!--<div style="float:left;">-->
		<td><a href="index.php">Home Page</a></td>
	<!--</div>-->
	
	<td>
	<?php
		if(isset($_SESSION["username"])){
			echo "Logged in as <strong>".$_SESSION["username"]."</strong>";
		} else {
			echo "You are not logged in";
		}
	?>
	</td>
	
	<td>
		<form action="search.php" method="post" style="margin:0">
			Search Groups/Events:
			<input name="search" />
			<input type="submit" value="Search" />
		</form>
	</td>
		
	
	<!--<div style="float:right">-->
	<td style="text-align:right;">
		<a href="groups.php">List of Groups</a> | 
		<a href="events.php">List of Events</a> |
		<a href="interests.php">List of Interests</a> | 
		<a href="user.php">My Profile</a> | 
		<a href="logout.php">Log Out</a>
	</td>
	<!--</div>-->
	
		</tr>
	</table>
	
</div>

<div id="main">

<!-- End Body Header -->