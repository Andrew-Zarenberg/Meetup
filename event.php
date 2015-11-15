<?php include("include.php"); ?>

<?php

	$invald = 0;
	if(isset($_GET["id"])){
		$event_id = intval($_GET["id"]);
		
		if($stmt = $mysqli->prepare("SELECT event.title, event.description, event.start_time, event.end_time, location.lname, location.description, location.street, location.city, location.zip FROM event, location WHERE event.lname=location.lname AND event.zip=location.zip AND event.event_id=?")){
			$stmt->bind_param("i",$event_id);
			$stmt->execute();
			$stmt->bind_result($event_title, $event_description, $event_start, $event_end, $location_name, $location_description, $location_street, $location_city, $location_zipcode);
			if($stmt->fetch()){
			
				/*
				 * START IF EVENT FOUND
				 */
				 
				// process any actions
				
				
				// action bar (store in variable because used on top and bottom)
				
				// find out if user is authorized
				
			?>
	
<html>
	<head>
	
		<title><?php echo $event_title; ?></title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<?php print_errors($error, $success); ?>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>

<?php
			}
		}
	}
?>