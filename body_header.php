<!-- Start Body Header -->

<div id="top">
	
	<table class="noborder" cellspacing="0">
		<tr>
		
	<!--<div style="float:left;">-->
		<td style="padding:0;"><a href="index.php">Home Page</a> | <a href="readme.php">README</a></td>
	<!--</div>-->
	
	<td>
	<?php
		if(isset($username)){
			echo "Logged in as <strong>".htmlentities($username)."</strong>";
		} else {
			echo "You are not logged in";
		}
	?>
	</td>
	
	<td>
		<form action="search.php" method="get" style="margin:0">
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
		
		<?php if(isset($username)){ ?>
			<a href="user.php">My Profile</a> | 
			<a href="logout.php">Log Out</a>
		<?php } else { ?>
			<a href="register.php">Register</a> | 
			<a href="login.php">Login</a>
		<?php } ?>
	</td>
	<!--</div>-->
	
		</tr>
	</table>
	
</div>

<div id="main">

<!-- End Body Header -->