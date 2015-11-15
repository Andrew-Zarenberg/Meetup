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
			?>
			
			<div id="title">My Dashboard</div>
		
			<?php print_errors($error, $success); ?>
			
			<div id="main_box">
			
				<table cellspacing="0">
					<tr>
						<th>Upcoming Events</th>
					</tr>
				</table>
			
				<table cellspacing="0">
					<tr>
						<th colspan="4" class="table_header">My Groups</th>
					</tr>
					
					<tr>
						<th>Name</th>
						<th>Interest</th>
						<th>Members</th>
						<th>Creator</th>
					</tr>
					
					<?php
					if($stmt = $mysqli->prepare('SELECT g.group_id, g.group_name, g.description, g.username, count("SELECT * FROM groupuser WHERE g.group_id=g.group_id") num_members, groupinterest.interest_name FROM `group` g, groupinterest, groupuser WHERE g.group_id=groupinterest.group_id AND g.group_id=groupuser.group_id AND groupuser.username=?')){
						$stmt->bind_param("s",$_SESSION["username"]);
						$stmt->execute();
						$stmt->bind_result($group_id, $group_name, $group_description, $group_creator, $num_members, $group_interest);
						while($stmt->fetch()){
							echo '<tr><td class="group_info"><div class="group_name"><a href="group.php?id='.$group_id.'">'.$group_name.'</a></div><div class="group_description">'.$group_description.'</div></td>';
							echo '<td class="group_interest"><a href="interest.php?interest='.$group_interest.'">'.$group_interest.'</a></td>';
							echo '<td class="group_members num">'.$num_members.'</td>';
							echo '<td class="group_creator"><a href="user.php?username='.$group_creator.'">'.$group_creator.'</a></td></tr>';
						}
						$stmt->close();
					}
					?>
				</table>
			</div>
			
			<?php } ?>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>