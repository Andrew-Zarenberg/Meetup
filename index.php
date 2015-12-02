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
			
			<!--<div id="main_box">-->
			
				<table cellspacing="0">
					<tr>
						<th class="table_header">Upcoming Events</th>
					</tr>
					
					<tr>
						<th><a href="events.php">Browse All Events</a></th>
					</tr>
				</table>
			
				<br />
			
				<table cellspacing="0">
					<tr>
						<th colspan="4" class="table_header">My Groups</th>
					</tr>
					
					
					
					<?php
					if($stmt = $mysqli->prepare("SELECT g.group_id, g.group_name, g.description, g.username, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=g.group_id) num_members FROM `group` g, groupuser WHERE g.group_id=groupuser.group_id AND groupuser.username=?")){
						$stmt->bind_param("s",$_SESSION["username"]);
						$stmt->execute();
						$stmt->store_result();
						$stmt->bind_result($group_id, $group_name, $group_description, $group_creator, $num_members);
						
						$groups = $stmt;
						
						if($groups->num_rows == 0){
							echo '<tr><th colspan="4">You are not in any groups!<br />Why don\'t you <a href="groups.php">browse all groups</a> to find one?</th></tr>';
						} else {
							echo '<tr><th colspan="4"><a href="groups.php">Browse All Groups</a></th></tr><tr><th>Name</th><th>Interest</th><th>Members</th><th>Creator</th></tr>';
							
							while($groups->fetch()){
								echo '<tr><td class="group_info"><div class="group_name"><a href="group.php?id='.$group_id.'">'.$group_name.'</a></div><div class="group_description">'.$group_description.'</div></td>';
								
								echo '<td class="group_interest">';
								if($stmt = $mysqli->prepare("SELECT interest_name FROM groupinterest WHERE group_id=? ORDER BY interest_name")){
									$stmt->bind_param("i", $group_id);
									$stmt->execute();
									$stmt->bind_result($interest_name);
									while($stmt->fetch()){
										echo '<div><a href="interest.php?name='.$interest_name.'">'.$interest_name.'</a></div>';
									}
									$stmt->close();
								} else echo 'fail';
								echo '</td>';
								
								echo '<td class="group_members num">'.$num_members.'</td>';
								echo '<td class="group_creator"><a href="user.php?username='.$group_creator.'">'.$group_creator.'</a></td></tr>';
							}
						}
						$groups->close();
					}
					
					?>
				</table>
			<!--</div>-->
			
			<?php } ?>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>