<?php include("include.php"); ?>

<?php
	$group_authorized = false;

	$auth_actions = "";
	$invald = 0;
	if(isset($_GET["id"])){
		$event_id = intval($_GET["id"]);
		
		if($stmt = $mysqli->prepare("SELECT event.title, event.description, event.start_time, event.end_time, location.lname, location.description, location.street, location.city, location.zip, g.group_id, g.group_name, g.username FROM event, location, `group` g WHERE event.lname=location.lname AND event.zip=location.zip AND g.group_id=event.group_id AND event.event_id=?")){
			$stmt->bind_param("i",$event_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($event_title, $event_description, $event_start, $event_end, $location_name, $location_description, $location_street, $location_city, $location_zipcode, $group_id, $group_name, $group_creator);
			
			$event = $stmt;
			if($event->fetch()){
			
				/*
				 * START IF EVENT FOUND
				 */
				 
				 
				
				// action bar (store in variable because used on top and bottom)
				$actions = '<div class="actions"><a href="events.php">Back to List of Events</a> | <a href="group.php?id='.$group_id.'">Back to Group: '.$group_name.'</a>';
				
				// status bar
				$status = "";
				 
				if(isset($_SESSION["username"])){
					
					// check if user is in group (and then if authorized)
					$group_member = false;					
					if($stmt = $mysqli->prepare("SELECT authorized FROM groupuser WHERE group_id=? AND username=?")){
						$stmt->bind_param("is", $group_id, $_SESSION["username"]);
						$stmt->execute();
						$stmt->bind_result($g_authorized);
						if($stmt->fetch()){
							$group_member = true;
							$group_authorized = $g_authorized;
						}
						$stmt->close();
					}
					 
					 
					// process any actions - only do actions if member is part of group
					if(isset($_GET["action"])){
						//if($group_member){
							switch($_GET["action"]){
								case "leave":								
									if($stmt = $mysqli->prepare("DELETE FROM eventuser WHERE event_id=? AND username=?")){
										$stmt->bind_param("is",$event_id,$_SESSION["username"]);
										$stmt->execute();
										$stmt->close();
										$success[] = "Successfully un-RSVP'd from event.";
									}
									break;
									
								case "join":
								
									// If user not in group, add user to group
									if(!$group_member){		
										$user_auth = 0;
										if($group_creator == $_SESSION["username"]){
											$user_auth = 1;
										}
									
										if($stmt = $mysqli->prepare("INSERT INTO groupuser VALUES(?, ?, ?)")){
											$stmt->bind_param("isi",$group_id, $_SESSION["username"], $user_auth);
											$stmt->execute();
											$stmt->close();
											
											$success[] = "Successfully joined group.";
										}
										$group_member = true;
									}
									
									
									
									if($stmt = $mysqli->prepare("INSERT INTO eventuser VALUES(?, ?, 1, 0)")){
										$stmt->bind_param("is",$event_id, $_SESSION["username"]);
										$stmt->execute();
										$stmt->close();
										$success[] = "Successfully RSVP'd to event.";
									}
									break;
							
							}
						//} else {
						//	$error[] = "You must be a group member to complete that action.";
						//}
					}
					
				
					// find out if user is rsvp
					if($group_member){
						if($stmt = $mysqli->prepare("SELECT rsvp FROM eventuser WHERE username=? AND event_id=? AND rsvp=1")){
							$stmt->bind_param("si",$_SESSION["username"],$event_id);
							$stmt->execute();
							if($stmt->fetch()){
								$status .= '<span class="status_member">RSVP\'d</span>';
								$actions .= ' | <a href="event.php?id='.$event_id.'&action=leave" class="bad">Un-RSVP from Event</a>';
							} else {
								$actions .= ' | <a href="event.php?id='.$event_id.'&action=join" class="good">RSVP to Event</a>';
							}
							$stmt->close();
						}
					} else {
						//$actions .= ' | You must be a group member to RSVP to this event';
						$actions .= ' | <a href="event.php?id='.$event_id.'&action=join" class="good">Join Group and RSVP to Event</a>';
					}
				}
				
				$actions .= '</div>';
				
				if($group_authorized){
					$auth_actions .= '<a href="event.php?id='.$event_id.'&action=del" class="bad">Delete Event</a>';
				}
			?>
	
<html>
	<head>
	
		<title><?php echo $event_title; ?></title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php 
			include("body_header.php"); 	
		?>
		
		
		<div id="title"><?php echo $event_title; ?>
			<div id="description">Hosted by <strong><?php echo $group_name; ?></strong></div>
		</div>
		<?php 
			if($status != ""){
				echo '<div id="status">'.$status.'</div>';
			}
					
			print_errors($error, $success); 
			
			if($auth_actions != ""){
				echo '<div class="auth_actions"><div style="font-weight:bold;">Authorized User Actions:</div>'.$auth_actions.'</div>';
			}
			
			echo $actions;
		?>
		
		<div>
			<table cellspacing="0" class="box">
				<tr>
					<td style="width:50%;">
						<?php
							echo '<div><strong>Hosted by: </strong> <a href="group.php?id='.$group_id.'">'.$group_name.'</a></div>';
							echo '<strong>Event Description:</strong><br />'.$event_description;
						?>
					</td>
					
					<td style="width:50%;" rowspan="3">
						<?php
							echo '<strong>Members RSVP:</strong>';
							
							if($stmt = $mysqli->prepare("SELECT username FROM eventuser WHERE event_id=? AND rsvp=1")){
								$stmt->bind_param("i",$event_id);
								$stmt->execute();
								$stmt->bind_result($username);
								
								while($stmt->fetch()){
									echo '<div><a href="user.php?username='.$username.'">'.$username.'</a></div>';
								}
								$stmt->close();
							}
						?>
					</td>
				</tr>
				
				<tr>
					<td>
						<strong>Location:</strong>
						<div><?php echo '<a href="location.php?name='.$location_name.'&zipcode='.$location_zipcode.'">'.$location_name.'</a>'; ?></div>
						<div><?php echo $location_street.' '.$location_zipcode.', '.$location_city; ?></div>
						<br />
						<div><?php echo $location_description; ?></div>
					</td>
				</tr>
					
					
				</tr>
				
			</table>
		</div>
							
		
		
		<?php 
			echo $actions;
			include("body_footer.php"); 
		?>
	</body>
</html>

<?php
			$event->close();
			}
		}
	}
?>