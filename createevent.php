<?php include("include.php");

$MONTH = array("","January","February","March","April","May","June","July","August","September","October","November","December");
 ?>

<?php

	$invalid = 0;
	if(isset($_GET["id"])){
		$group_id = intval($_GET["id"]);
		
		if($stmt = $mysqli->prepare("SELECT g.group_name FROM `group` g WHERE g.group_id=?")){
			$stmt->bind_param("i",$group_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($group_name);
			
			$group = $stmt;
			if($group->fetch()){
			
			?>

<html>
	<head>
	
		<title>Create New Event</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Create New Event</div>
		<?php print_errors($error, $success); ?>
		
		<form action="createevent.php?id=<?php echo $group_id; ?>" method="post">
			<table cellspacing="0">
				<tr>
					<th>Group</th>
					<td><?php echo $group_name; ?>
				</tr>
				
				<tr>
					<th>Event Name</th>
					<td><input name="name" /></td>
				</tr>
				
				<tr>
					<th>Start Time</th>
					<td>
						<select name="start_month">
							<?php
								$current_month = date("n");
								for($x=1;$x<=12;$x++){
									echo '<option value="'.$x.'"';
									if($current_month == $x) echo ' selected';
									echo '>'.$MONTH[$x].'</option>';
								}
							?>
						</select>
						
						<select name="start_day">
							<?php
								$current_day = date("d");
								for($x=1;$x<=31;$x++){
									echo '<option value="'.$x.'"';
									if($current_day == $x) echo ' selected';
									echo '>'.$x.'</option>';
								}
							?>
						</select>
						
						<!--<input name="start_day" size="2" placeholder="DD" /> / -->
						<input name="start_year" size="4" placeholder="YYYY" /> at 
						<input name="start_hour" size="2" placeholder="hh" />:
						<input name="start_minute" size="2" placeholder="mm" />
						<select name="start_ampm">
							<option value="1">AM</option>
							<option value="2">PM</option>
						</select>
					</td>
				
				<tr>
					<th>Location</th>
					<td>
						<select name="location">
							<option value="-1">Select Location...</option>
							<?php
								if($stmt = $mysqli->prepare("SELECT lname, zip, city FROM location")){
									$stmt->execute();
									$stmt->bind_result($location_name, $location_zipcode, $location_city);
									
									while($stmt->fetch()){
										echo '<option value="'.$location_zipcode.$location_name.'">'.$location_name.' - '.$location_zipcode.', '.$location_city.'</option>';
									}
								}	
							
							?>
							
						</select>
					</td>
				</tr>
				
				<tr>
					<th>Description</th>
					<td>
						<textarea name="description" style="width:100%;" rows="10"></textarea>
					</td>
				</tr>
				
				<tr>
					<th colspan="2">
						<input type="submit" value="Create Event" />
					</th>
				</tr>
			</table>
		</form>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>

<?php
		}
	}
}
?>