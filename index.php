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
				$success[] = "Successfully logged in!";
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
			if(isset($username)){
			?>
			
			<div id="title">My Dashboard</div>
		
			<?php print_errors($error, $success); ?>
			
			<!--<div id="main_box">-->
			
			

				<table cellspacing="0">
					<tr>
						<th colspan="3" class="table_header">Check Out These Groups!</th>
					</tr>
					
					<tr>
						<th>Top 3 Groups</th>
						<th>Bottom 3 Groups</th>
						<th>Random 3 Groups</th>
					</tr>
					
					<tr>
						<td style="width:33%;vertical-align:top">
							<div>These are the most popular groups, perhaps you should see what all the fuss is about!</div>
						
						<?php
							if($stmt = $mysqli->prepare("SELECT g.group_id, g.group_name, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=g.group_id) num_members FROM `group` g ORDER BY num_members DESC LIMIT 3")){
								$stmt->execute();
								$stmt->bind_result($group_id, $group_name, $num_members);
								while($stmt->fetch()){
									echo '<div><a href="group.php?id='.$group_id.'">'.$group_name.'</a> - <strong>'.$num_members.'</strong> members</div>';
								}
							}
						?>
						
						</td>
						
						<td style="width:34%;vertical-align:top">
							<div>Show these groups some love and see what they have to offer!</div>
							
						<?php
							if($stmt = $mysqli->prepare("SELECT g.group_id, g.group_name, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=g.group_id) num_members FROM `group` g ORDER BY num_members LIMIT 3")){
								$stmt->execute();
								$stmt->bind_result($group_id, $group_name, $num_members);
								while($stmt->fetch()){
									echo '<div><a href="group.php?id='.$group_id.'">'.$group_name.'</a> - <strong>'.$num_members.'</strong> members</div>';
								}
							}
						?>
						</td>
						
						<td style="width:33%;vertical-align:top;">
							<div>Perhaps one of these groups will capture your interest?</div>
							
						<?php
							// For random, make sure user is NOT in that group
							if($stmt = $mysqli->prepare("SELECT g.group_id, g.group_name, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=g.group_id) num_members FROM `group` g WHERE ? NOT IN (SELECT username FROM groupuser WHERE groupuser.group_id=g.group_id) ORDER BY RAND() DESC LIMIT 3")){
								$stmt->bind_param("s",$username);
								$stmt->execute();
								$stmt->bind_result($group_id, $group_name, $num_members);
								while($stmt->fetch()){
									echo '<div><a href="group.php?id='.$group_id.'">'.$group_name.'</a> - <strong>'.$num_members.'</strong> members</div>';
								}
							}
						?>
						</td>
						
					</tr>
				</table><br />
			
			
			
				<table cellspacing="0">
					<tr>
						<th colspan="5" class="table_header">My Events in the next 3 days</th>
					</tr>
					
					
					<?php
						if($stmt = $mysqli->prepare("SELECT e.event_id, e.title, e.description, e.start_time, e.end_time, e.group_id, g.group_name, e.lname, e.zip, l.street, l.city, (SELECT count(*) FROM eventuser WHERE eventuser.event_id=e.event_id) num_rsvp FROM `event` e, `group` g, location l, eventuser ev WHERE e.group_id=g.group_id AND e.lname=l.lname AND e.zip=l.zip AND e.event_id=ev.event_id AND ev.username=? ORDER BY e.start_time")){
							$stmt->bind_param("s",$username);
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result($event_id, $event_title, $event_description, $event_start_time, $event_end_time, $group_id, $group_name, $location_name, $location_zipcode, $location_street, $location_city, $num_rsvp);
					
							$event = $stmt;
							
							if($event->num_rows == 0){
								echo '<tr><th colspan="5">You have not RSVP\'d for any events!<br />Why don\'t you <a href="events.php">browse all events</a> to find one?</th></tr>';
							} else {
								echo '<tr><th colspan="5"><a href="events.php">Browse All Events</a></th></tr><tr><th>Name</th><th>Host</th><th>Start / End</th><th>Location</th><th>RSVPs</th></tr>';
									
								
								while($event->fetch()){
								
									// Because time, have to do check with PHP and not in SQL
									$ts = (new Datetime($event_start_time))->getTimestamp();
									if(time() > $ts || time()+60*60*24*3 < $ts) continue;
									
									echo '<tr><td class="group_info"><div class="group_name"><a href="event.php?id='.$event_id.'">'.$event_title.'</a></div><div class="group_description">'.$event_description.'</div></td>';
									echo '<td><a href="group.php?id='.$group_id.'">'.$group_name.'</a></td>';
									echo '<td>'.(new Datetime($event_start_time))->format($EVENT_DATE_FORMAT).'<br />'.(new Datetime($event_end_time))->format($EVENT_DATE_FORMAT).'</td>';
									echo '<td><div><a href="location.php?name='.$location_name.'&zipcode='.$location_zipcode.'">'.$location_name.'</a></div><div>'.$location_street.' '.$location_zipcode.', '.$location_city.'</div></td>';
									echo '<td class="num">'.$num_rsvp.'</td>';
									echo '</tr>';
								}
							}
							$event->close();
						}
					?>
				</table>
			
				<br />
			
				<table cellspacing="0">
					<tr>
						<th colspan="4" class="table_header">My Groups</th>
					</tr>
					
					
					
					<?php
					if($stmt = $mysqli->prepare("SELECT g.group_id, g.group_name, g.description, g.username, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=g.group_id) num_members FROM `group` g, groupuser WHERE g.group_id=groupuser.group_id AND groupuser.username=?")){
						$stmt->bind_param("s",$username);
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
			
			
			
			<?php } else { 
			// If not logged in ?>
			
			<div id="title">Meetup</div>
			
			<?php print_errors($error, $success); ?>
			
			<table cellspacing="0">
				<tr>
					<th class="table_header">Welcome to Meetup!</th>
				</tr>
				
				<tr>
					<th class="table_header">New here?  <a href="register.php">Register</a> an account now!</th>
				</tr>
				
				<tr>
					<th class="table_header">Returning user?  <a href="login.php">Login</a>!</th>
				</tr>
				
				<tr>
					<td>
						<div>
							Welcome to Meetup, a place to connect with other people with common interests!
							
							<br /><br />
							This application has been designed with the user in mind, so everything should be straight forward.  If anything is unclear you can see the <a href="readme.php">README</a> file.
							
							<br />	
						</div>
					</td>
				</tr>
			</table>
			
			
			<?php } ?>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>