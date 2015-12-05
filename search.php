<?php include("include.php"); ?>

<?php
	$search = htmlentities($_GET["search"]);
?>

<html>
	<head>
	
		<title><?php echo $search; ?></title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Search</div>
		<?php print_errors($error, $success); ?>
		
		<table cellspacing="0">
			<tr>
				<th class="table_header" colspan="5">Groups</th>
			</tr>
			
			<tr>
				<th>Match</th>
				<th>Name</th>
				<th>Interests</th>
				<th>Members</th>
				<th>Creator</th>
			</tr>
			
			<?php
				// 5 stars = EXACT match
				// 4 stars = GROUP% OR %GROUP
				// 3 stars = %GROUP%
				
				// because must pass by reference......
				$searchP = $search.'%';
				$Psearch = '%'.$search;
				$PsearchP = '%'.$search.'%';
				
				if($stmt = $mysqli->prepare("SELECT strength, group_id, group_name, description, username, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=results.group_id) num_members FROM ((SELECT *, 5 strength FROM `group` g WHERE g.group_name=?) UNION (SELECT *, 4 strength FROM `group` g WHERE g.group_name LIKE ? OR g.group_name LIKE ?) UNION (SELECT *, 3 strength FROM `group` g WHERE g.group_name LIKE ?)) results GROUP BY group_id ORDER BY strength DESC")){
					$stmt->bind_param("ssss", $search, $searchP, $Psearch, $PsearchP);
					$stmt->execute();
					$stmt->bind_result($strength, $group_id, $group_name, $group_description, $group_creator, $num_members);
					$stmt->store_result();
					
					$groups = $stmt;
					while($groups->fetch()){
						echo '<tr><td>('.$strength.' / 5) ';
						
						for($x=0;$x<5;$x++){
							if($strength > $x) echo 'X';
							//else echo 'o';
							
							echo ' ';
						}
						
						echo '</td><td class="group_info"><div class="group_name"><a href="group.php?id='.$group_id.'">'.$group_name.'</a></div><div class="group_description">'.$group_description.'</div></td>';
						//echo '<td class="group_interest"><a href="interest.php?interest='.$group_interest.'">'.$group_interest.'</a></td>';
						
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
					$groups->close();
				}
					
				
			?>
			
		</table>
		
		<br />
		
		<table cellspacing="0">
			<tr>
				<th class="table_header" colspan="6">Events</th>
			</tr>
			
			<tr>
				<th>Match</th>
				<th>Name</th>
				<th>Host</th>
				<th>Start / End</th>
				<th>Location</th>
				<th>RSVPs</th>
			</tr>
			
			<?php
				if($stmt = $mysqli->prepare("SELECT strength, e.event_id, e.title, e.description, e.start_time, e.end_time, e.group_id, g.group_name, e.lname, e.zip, l.street, l.city, (SELECT count(*) FROM eventuser WHERE eventuser.event_id=e.event_id) num_rsvp FROM ((SELECT *, 5 strength FROM `event` e WHERE e.title=?) UNION (SELECT *, 4 strength FROM `event` e WHERE e.title LIKE ? OR e.title LIKE ?) UNION (SELECT *, 3 strength FROM `event` e WHERE e.title LIKE ?)) e, `group` g, location l WHERE e.group_id=g.group_id AND e.lname=l.lname AND e.zip=l.zip GROUP BY event_id ORDER BY strength DESC")){
					$stmt->bind_param("ssss", $search, $Psearch, $searchP, $PsearchP);
					$stmt->execute();
					$stmt->bind_result($strength, $event_id, $event_title, $event_description, $event_start_time, $event_end_time, $group_id, $group_name, $location_name, $location_zipcode, $location_street, $location_city, $num_rsvp);
					
					$event = $stmt;
					while($event->fetch()){
					
						// Because time, have to do check with PHP and not in SQL
						if(time() > (new Datetime($event_start_time))->getTimestamp()) continue;
						
						echo '<tr><td>('.$strength.' / 5) ';
						
						for($x=0;$x<5;$x++){
							if($strength > $x) echo 'X';
							//else echo 'o';
							
							echo ' ';
						}
						
						echo '</td><td class="group_info"><div class="group_name"><a href="event.php?id='.$event_id.'">'.$event_title.'</a></div><div class="group_description">'.$event_description.'</div></td>';
						echo '<td><a href="group.php?id='.$group_id.'">'.$group_name.'</a></td>';
						echo '<td>'.(new Datetime($event_start_time))->format($EVENT_DATE_FORMAT).'<br />'.(new Datetime($event_end_time))->format($EVENT_DATE_FORMAT).'</td>';
						echo '<td><div><a href="location.php?name='.$location_name.'&zipcode='.$location_zipcode.'">'.$location_name.'</a></div><div>'.$location_street.' '.$location_zipcode.', '.$location_city.'</div></td>';
						echo '<td class="num">'.$num_rsvp.'</td>';
						echo '</tr>';
					}
					$event->close();
				}
			?>
		</table>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>