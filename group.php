<?php include("include.php"); ?>

<?php
				 
				
	$invalid = 0;
	if(isset($_GET["id"])){
		$group_id = intval($_GET["id"]);
		
		//if($stmt = $mysqli->prepare("SELECT g.group_name, g.description FROM `group` g WHERE g.group_id=?")){
		if($stmt = $mysqli->prepare('SELECT g.group_name, g.description, g.username, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=g.group_id) num_members, (SELECT count(*) FROM groupinterest WHERE groupinterest.group_id=g.group_id) num_interests FROM `group` g WHERE g.group_id=?')){
			$stmt->bind_param("i",$group_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($group_name, $group_description, $group_creator, $num_members, $num_interests);
			
			$group = $stmt;
			if($group->fetch()){
			
				if(isset($_GET["eventdel"]) && $_GET["eventdel"] == "true") $success[] = "Event successfully deleted.";
				if(isset($_GET["addannounce"]) && $_GET["addannounce"] == "true") $success[] = "Announcement successfully created.";
			
				/*
				 * START IF GROUP FOUND
				 */
				 
				
				// process any actions
				if(isset($_GET["action"])){
					switch($_GET["action"]){
						case "leave":
							// Leaving a group will un-RSVP from ALL events in the group (since only group members may RSVP)
							if($stmt = $mysqli->prepare("DELETE FROM groupuser WHERE group_id=? AND username=?")){
								$stmt->bind_param("is",$group_id, $user);
								$stmt->execute();
								$stmt->close();
								
								
								$success[] = "Successfully left group.";
								
								// Since num_members is already chosen while user is still in group, decrement.
								$num_members--;

								// Loop through events to get event_id then delete user RSVP
								if($stmt = $mysqli->prepare("SELECT event_id, title FROM event WHERE group_id=?")){
									$stmt->bind_param("i", $group_id);
									$stmt->execute();
									$stmt->store_result();
									$stmt->bind_result($event_id, $event_name);
									
									$event = $stmt;
									while($event->fetch()){
									
										// I know SELECT is not necessary to delete entries from eventuser, but to display
										// feedback messages to user it is necessary.  Let user know each event they are now un-RSVP'd from
										if($stmt = $mysqli->prepare("SELECT event_id FROM eventuser WHERE event_id=? AND username=?")){
											$stmt->bind_param("is", $event_id, $user);
											$stmt->execute();
											if($stmt->fetch()){
												$success[] = "Un-RSVP'd from event: ".$event_name.".";
											}
											$stmt->close();
										}
									
										if($stmt = $mysqli->prepare("DELETE FROM eventuser WHERE event_id=? AND username=?")){
											$stmt->bind_param("is", $event_id, $user);
											$stmt->execute();
											$stmt->close();
										}
										
									}
									$event->close();
								}
								
							}
						
							break;
							
						case "join":
						
							$user_auth = 0;
							if($group_creator == $username){
								$user_auth = 1;
							}
						
							if($stmt = $mysqli->prepare("INSERT INTO groupuser VALUES(?, ?, ?)")){
								$stmt->bind_param("isi",$group_id, $username, $user_auth);
								$stmt->execute();
								$stmt->close();
								
								$success[] = "Successfully joined group.";
							}
							
							$num_members++;
							
							break;
							
						case "delete":
							// Only group creator may delete a group
							if($username == $group_creator){
								
								// First delete all group interests
								if($stmt = $mysqli->prepare("DELETE FROM groupinterest WHERE group_id=?")){
									$stmt->bind_param("i",$group_id);
									$stmt->execute();
									$stmt->close();
								}
								
								// Next delete all memberships
								if($stmt = $mysqli->prepare("DELETE FROM groupuser WHERE group_id=?")){
									$stmt->bind_param("i",$group_id);
									$stmt->execute();
									$stmt->close();
								}
								
								// Deleve all RSVP's to events
								if($stmt = $mysqli->prepare("DELETE FROM eventuser WHERE event_id IN (SELECT event_id FROM event WHERE group_id=?)")){
									$stmt->bind_param("i",$group_id);
									$stmt->execute();
									$stmt->close();
								}
								
								// Next delete all events
								if($stmt = $mysqli->prepare("DELETE FROM event WHERE group_id=?")){
									$stmt->bind_param("i",$group_id);
									$stmt->execute();
									$stmt->close();
								}
								
								// Lastly delete the group
								if($stmt = $mysqli->prepare("DELETE FROM `group` WHERE group_id=?")){
									$stmt->bind_param("i",$group_id);
									$stmt->execute();
									$stmt->close();
								}
								
								header("Location: groups.php?del=true");
								
							} else {
								$error[] = "Only the group creator may delete this group.";
							}
							break;
							
							
						// Authorize user - GROUP CREATOR ONLY
						case "auth": 
							if($group_creator == $username){
								if(!isset($_POST["user"]) || $_POST["user"] == $username) $error[] = "Invalid input";
								else {
									
									if($stmt = $mysqli->prepare("UPDATE groupuser SET authorized=1 WHERE username=?")){
										$stmt->bind_param("s",$_POST["user"]);
										$stmt->execute();
									}
									
									$success[] = "Successfully authorized user.";
								}
							} else $error[] = "You do not have permission to perform that action.";
							break;
							
						// UN-Authorize user - GROUP CREATOR ONLY
						case "unauth": 
							if($group_creator == $username){
								if(!isset($_POST["user"]) || $_POST["user"] == $username) $error[] = "Invalid input";
								else {
									
									if($stmt = $mysqli->prepare("UPDATE groupuser SET authorized=0 WHERE username=?")){
										$stmt->bind_param("s",$_POST["user"]);
										$stmt->execute();
									}
									
									$success[] = "Successfully un-authorized user.";
								}
							} else $error[] = "You do not have permission to perform that action.";
							break;
							
						case "delannounce":
							$a_id = intval($_GET["a_id"]);
							
							// If group creator allow to delete - if not check if user created announcement
							$can_delete = false;
							if($group_creator == $username) $can_delete = true;
							else {
								if($stmt = $mysqli->prepare("SELECT announcement_id FROM groupannouncement WHERE announcement_id=? AND username=?")){
									$stmt->bind_param("is",$a_id,$username);
									$stmt->execute();
									if($stmt->fetch()){
										$can_delete = true;
									}
									$stmt->close();
								}
							}
							
							if($can_delete){
								if($stmt = $mysqli->prepare("DELETE FROM groupannouncement WHERE announcement_id=?")){
									$stmt->bind_param("i",$a_id);
									$stmt->execute();
									
									$success[] = "Successfully deleted announcement.";
								} else echo 'error';
							} else $error[] = "You do not have permission to perform that action.";
							
							break;
							
					}
				}
				 
				 
				 
				 // action bar (store in variable because used on top and bottom)
				$actions = '<div class="actions"><a href="groups.php">Back to List of Groups</a>';
				
				// Authorized actions
				$auth_actions = "";
				$creator_actions = "";
				
				// status bar
				$status = "";
				
				// find out if user is authorized
				if(isset($username)){
				
				
					if($stmt = $mysqli->prepare("SELECT authorized FROM groupuser WHERE group_id=? AND username=?")){
						$stmt->bind_param("is",$group_id,$username);
						$stmt->execute();
						$stmt->store_result();
						$stmt->bind_result($auth);
						
						$authorized_user = $stmt;
						if($authorized_user->fetch()){
						
							$status .= '<span class="status_member">Group Member</span>';
							if($auth == 1){
								$auth_actions = '<a href="createannouncement.php?id='.$group_id.'">Add Announcement</a> | <a href="createevent.php?id='.$group_id.'">Create New Event</a>';
								//$creator_actions .= '<a href="editgroup.php?id='.$group_id.'">Edit Group</a> | ';
								//$creator_actions .= '<a href="createevent.php?id='.$group_id.'">Create New Event</a>';
								
								if($username == $group_creator){
									// For auth/unauth use $username as default value to ensure no false negatives - never will apply action to group creator
								
									$creator_actions .= '<a href="group.php?id='.$group_id.'&action=delete" class="bad">Delete Group</a>';
									
									$creator_actions .= '<br /><br /><form action="group.php?id='.$group_id.'&action=auth" method="post">Authorized Group Member: <select name="user"><option value="'.$username.'">Select a Member...</option>';
									if($stmt = $mysqli->prepare("SELECT username FROM groupuser WHERE group_id=? AND authorized=0 ORDER BY username")){
										$stmt->bind_param("i",$group_id);
										$stmt->execute();
										$stmt->bind_result($user);
										while($stmt->fetch()){
											$creator_actions .= '<option value="'.$user.'">'.$user.'</option>';
										}
										$stmt->close();
									}
									
									$creator_actions .= '</select> <input type="submit" value="Authorize User" /></form>';
									
									
									// UNAUTHORIZE
									$creator_actions .= '<form action="group.php?id='.$group_id.'&action=unauth" method="post">Un-authorize Group Member: <select name="user"><option value="'.$username.'">Select a Member...</option>';
									if($stmt = $mysqli->prepare("SELECT username FROM groupuser WHERE group_id=? AND authorized=1 AND username!=? ORDER BY username")){
										$stmt->bind_param("is",$group_id,$username);
										$stmt->execute();
										$stmt->bind_result($user);
										while($stmt->fetch()){
											$creator_actions .= '<option value="'.$user.'">'.$user.'</option>';
										}
										$stmt->close();
									}
									
									$creator_actions .= '</select> <input type="submit" value="Un-Authorize User" /></form>';
								}
								$status .= '<span class="status_authorized">Authorized User</span>';
							}
							$actions .= ' | <a href="group.php?id='.$group_id.'&action=leave" class="bad">Leave Group</a>';
						} else $actions .= ' | <a href="group.php?id='.$group_id.'&action=join" class="good">Join Group</a>';
						$authorized_user->close();
					}
				}
				
				$actions .= '</div>';
			?>
			
			
			
<html>
	<head>
	
		<title><?php echo $group_name; ?></title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		
		<div id="title"><?php echo $group_name; ?></div>
		<?php 
			if($status != ""){
				echo '<div id="status">'.$status.'</div>';
			}
			print_errors($error, $success); 
			
			if($auth_actions != ""){
				echo '<div class="auth_actions"><div style="font-weight:bold;">Authorized User Actions:</div>'.$auth_actions.'</div>';
			}
			
			
			if($creator_actions != ""){
				echo '<div class="creator_actions"><div style="font-weight:bold;">Group Creator Actions:</div>'.$creator_actions.'</div>';
			}
			
			echo $actions; 
		?>
		
		
		
		
		
		<div>
			<table cellspacing="0" class="box">
				<tr>
					<td style="width:50%;">
						<strong>Group Description:</strong><br />
						<?php echo nl2br($group_description); ?>
					</td>
					
					<td style="width:50%" rowspan="2">
						<?php
							echo '<div class="authorized"><strong>Group Creator:</strong> ';
							echo '<a href="user.php?username='.$group_creator.'">'.$group_creator.'</a>';
							echo '</div><br /><strong>Group Members: ('.$num_members.')</strong><br />';
						
							if($stmt = $mysqli->prepare("SELECT username, authorized FROM groupuser WHERE group_id=? ORDER BY authorized DESC, username")){
								$stmt->bind_param("i",$group_id);
								$stmt->execute();
								$stmt->bind_result($user, $authorized);
								while($stmt->fetch()){
									if($authorized) echo '<div class="authorized"><span>[Authorized]</span> ';
									else echo '<div>';
									echo '<a href="user.php?username='.$user.'">'.$user.'</a></div>';
								}
								$stmt->close();
							}
							
							echo '</td></tr><tr><td>';
						
							echo '<strong>Group Interests: ('.$num_interests.')	</strong><br />';
							
							if($stmt = $mysqli->prepare("SELECT interest_name FROM groupinterest WHERE group_id=? ORDER BY interest_name")){
								$stmt->bind_param("i",$group_id);
								$stmt->execute();
								$stmt->bind_result($interest_name);
								while($stmt->fetch()){
									echo '<a href="interest.php?name='.$interest_name.'">'.$interest_name.'</a> &nbsp; &nbsp; &nbsp; ';
								}
								$stmt->close();
							}
						?>
					</td>
						
				</tr>
			</table>
			<br />
			
			
			<table cellspacing="0" class="box">
				<tr>
					<th class="table_header" colspan="2">Announcements</th>
				</tr>
				
				<?php
					if($stmt = $mysqli->prepare("SELECT announcement_id, username, title, announcement, date FROM groupannouncement WHERE group_id=? ORDER BY date DESC")){
						$stmt->bind_param("i",$group_id);
						$stmt->execute();
						$stmt->store_result();
						$stmt->bind_result($a_id, $a_username, $a_title, $a_content, $a_date);
						
						
						if($stmt->num_rows == 0){
							echo '<tr><th colspan="2">No Announcements</th></tr>';
						}
						
						while($stmt->fetch()){
							echo '<tr><th class="username">'.$a_username.'</th>';
							echo '<td rowspan="2"><div style="font-size:25px;">'.$a_title.'</div><div>Posted on: <strong>'.(new Datetime($a_date))->format($EVENT_DATE_FORMAT).'</strong></div><br />'.nl2br($a_content).'</td>';
							echo '<tr><td class="actions" style="text-align:center;">';
							
							// ONLY group creator AND announcement creator have ability to delete
							if(isset($username) && ($group_creator == $username || $a_username == $username)){
								echo '<a href="group.php?id='.$group_id.'&action=delannounce&a_id='.$a_id.'">Delete</a>';
							}
							
							echo '</td></tr>';
							
							echo '<tr><td colspan="2" style="font-size:1px;padding:1px;background:black;">&nbsp;</td></tr>';
						}
					}
				?>
				
			</table>
			
			
			<br />
			<table cellspacing="0" class="box">
				<tr>
					<th class="table_header" colspan="4">Upcoming Events</th>
				</tr>
				
				
				
				<?php
					if($stmt = $mysqli->prepare("SELECT event.event_id, event.title, event.description, event.start_time, event.end_time, location.lname, location.description, location.street, location.city, location.zip FROM event, location WHERE event.lname=location.lname AND event.zip=location.zip AND event.group_id=? ORDER BY event.start_time")){
						$stmt->bind_param("i",$group_id);
						$stmt->execute();
						$stmt->bind_result($event_id, $event_title, $event_description, $event_start, $event_end, $location_name, $location_description, $location_street, $location_city, $location_zipcode);
						$stmt->store_result();
						
						if($stmt->num_rows == 0){
							echo '<tr><th colspan="4">No Events</th></tr>';
						} else {
							echo '<tr><th>Name</th><th>Start Time</th><th>End Time</th><th>Location</th></tr>';
						}
						
						while($stmt->fetch()){
						
							// Because time, have to do check with PHP and not in SQL
							if(time() > (new Datetime($event_start))->getTimestamp()) continue;
						
							echo '<tr><td><div><a href="event.php?id='.$event_id.'">'.$event_title.'</a></div>'.$event_description.'</td>';
							echo '<td>'.(new Datetime($event_start))->format($EVENT_DATE_FORMAT).'</td>';
							echo '<td>'.(new Datetime($event_end))->format($EVENT_DATE_FORMAT).'</td>';
							echo '<td><div><a href="location.php?name='.$location_name.'&zipcode='.$location_zipcode.'">'.$location_name.'</a></div><div>'.$location_street.' '.$location_zipcode.', '.$location_city.'</div></td></tr>';
						}
					}
				?>
			</table>
		</div>
		
		
		
		<?php 
			echo $actions;
			include("body_footer.php"); 
		?>
	</body>
</html>
			
			
			
			<?php
			/*
			 * END IF GROUP FOUND
			 */
				$group->close();
			} else $invalid = 1;
		} else $invalid = 1;
	} else $invalid = 1;
	
	if($invalid == 1){ 
		$actions = '<div class="actions"><a href="groups.php">Back to List of Groups</a></div>';
	?>
	

<html>
	<head>
	
		<title>Group Not Found</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Group Not Found</div>
		<?php echo $actions; ?>
		<div id="main_box">
			The group you are trying to access does not exist or has been deleted.
			<br /><br />
			<a href="groups.php">Back to List of Groups</a>
		</div>
		<?php echo $actions; ?>
		<?php include("body_footer.php"); ?>
	</body>
</html>
	
<?php } ?>
