<?php include("include.php"); ?>

<html>
	<head>
	
		<title>List of Events</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Events</div>
		<?php print_errors($error, $success); ?>
		
		<table cellspacing="0">
			<tr>
				<th class="table_header" colspan="5">Events</th>
			</tr>

			<tr>
				<th>Name</th>
				<th>Host</th>
				<th>Start / End</th>
				<th>Location</th>
				<th>RSVPs</th>
			</tr>
			
			<?php
				if($stmt = $mysqli->prepare("SELECT e.event_id, e.title, e.description, e.start_time, e.end_time, e.group_id, g.group_name, e.lname, e.zip, l.street, l.city, (SELECT count(*) FROM eventuser WHERE eventuser.event_id=e.event_id) num_rsvp FROM `event` e, `group` g, location l WHERE e.group_id=g.group_id AND e.lname=l.lname AND e.zip=l.zip ORDER BY e.start_time")){
					$stmt->execute();
					$stmt->bind_result($event_id, $event_title, $event_description, $event_start_time, $event_end_time, $group_id, $group_name, $location_name, $location_zipcode, $location_street, $location_city, $num_rsvp);
					
					$event = $stmt;
					while($event->fetch()){
					
						// Because time, have to do check with PHP and not in SQL
						if(time() > (new Datetime($event_start_time))->getTimestamp()) continue;
						
						echo '<tr><td class="group_info"><div class="group_name"><a href="event.php?id='.$event_id.'">'.$event_title.'</a></div><div class="group_description">'.$event_description.'</div></td>';
						echo '<td><a href="group.php?id='.$group_id.'">'.htmlentities($group_name).'</a></td>';
						echo '<td>'.(new Datetime($event_start_time))->format($EVENT_DATE_FORMAT).'<br />'.(new Datetime($event_end_time))->format($EVENT_DATE_FORMAT).'</td>';
						echo '<td><div><a href="location.php?name='.htmlentities($location_name).'&zipcode='.$location_zipcode.'">'.htmlentities($location_name).'</a></div><div>'.htmlentities($location_street).' '.$location_zipcode.', '.htmlentities($location_city).'</div></td>';
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