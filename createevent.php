<?php include("include.php");

$MONTH = array("","January","February","March","April","May","June","July","August","September","October","November","December");
$DAYS = array(-1, 31,        29,       31,      30,     31,   30,    31,    31,     30,         31,        30,        31); // Max days in month

// Not going to take leap years into account, too much unnecessary effort - february has 29 max days for this.
 ?>

<?php

	$actions = "";

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
			
				$auth_user = 0;
				// Check if user is authorized
				if($stmt = $mysqli->prepare("SELECT authorized FROM groupuser WHERE group_id=? AND username=?")){
					$stmt->bind_param("is",$group_id, $username);
					$stmt->execute();
					$stmt->bind_result($auth_user);
					$stmt->fetch();
					$stmt->close();
				}
			
				if($auth_user == 1){
				
					$actions = '<div class="actions"><a href="group.php?id='.$group_id.'">Back to Group: '.htmlentities($group_name).'</a></div>';
				
					// If submitted
					if(isset($_POST["name"])){
					
						// Make sure event name is specified
						if($_POST["name"] == "") $error[] = "You must enter an event name";
						
						// Start date
						$start_month = intval($_POST["start_month"]);
						$start_day = intval($_POST["start_day"]);
						$start_year = intval($_POST["start_year"]);
						$start_hour = intval($_POST["start_hour"]);
						$start_minute = intval($_POST["start_minute"]);
						$start_ampm = intval($_POST["start_ampm"]);
						if($start_month < 1 || $start_month > 12 || $start_day < 1 || $start_day > $DAYS[$start_month]){
							if($start_day > $DAYS[$start_month]) $error[] = "[Start Date] ".$MONTH[$start_month]." ".$start_day." does not exist.";
							else $error[] = "Invalid start date/time";
						} else {
							if($start_ampm != 1 && $start_ampm != 2) $error[] = "[Start Date] Invalid AM/PM input";
							else if($start_hour < 1 || $start_hour > 12 || $start_minute < 0 || $start_minute > 60) $error[] = "[Start Date] ".$_POST["start_hour"].":".$_POST["start_minute"]." is not a valid time.";
						}
						
						// End date
						$end_month = intval($_POST["end_month"]);
						$end_day = intval($_POST["end_day"]);
						$end_year = intval($_POST["end_year"]);
						$end_hour = intval($_POST["end_hour"]);
						$end_minute = intval($_POST["end_minute"]);
						$end_ampm = intval($_POST["end_ampm"]);
						if($end_month < 1 || $end_month > 12 || $end_day < 1 || $end_day > $DAYS[$end_month]){
							if($end_day > $DAYS[$end_month]) $error[] = "[End Date] ".$MONTH[$end_month]." ".$end_day." does not exist.";
							else $error[] = "Invalid end date/time";
						} else {
							if($end_ampm != 1 && $end_ampm != 2) $error[] = "[End Date] Invalid AM/PM input";
							else if($end_hour < 1 || $end_hour > 12 || $end_minute < 0 || $end_minute > 60) $error[] = "[End Date] ".$_POST["end_hour"].":".$_POST["end_minute"]." is not a valid time.";
						}
						
						
						
						// Verify event location
						// if other, create new location
						if($_POST["location"] == "other"){
						
							if($_POST["loc_name"] == "") $error[] = "[Location] You must specify a location name";
							// Assume location description is optional
							if($_POST["loc_street"] == "") $error[] = "[Location] You must specify a street address";
							if($_POST["loc_city"] == "") $error[] = "[Location] You must specify a city";
							if($_POST["loc_zip"] == "") $error[] = "[Location] You must specify a zipcode";
							else {
								$zipcode = intval($_POST["loc_zip"]);
								if($zipcode > 99999 || $zipcode < 1) $error[] = "[Location] You must specify a valid 5-digit zipcode.";
							}
							if($_POST["loc_latitude"] == "") $error[] = "[Location] You must specify a latitude";
							if($_POST["loc_longitude"] == "") $error[] = "[Location] You must specify a longitude";
						
						} else {
							if(strlen($_POST["location"]) < 6) $error[] = "Invalid Location";
							else {
								$location_zipcode = substr($_POST["location"],0,5);
								$location_name = substr($_POST["location"],5);
														
							}
						}
						
						// If no errors, begin adding event
						if(count($error) == 0){
							if($start_ampm == 2 && $start_hour < 11) $start_hour += 12;
							$start_date = new DateTime();
							$start_date->setDate($start_year, $start_month, $start_day);
							$start_date->setTime($start_hour, $start_minute, 0);
							
							if($end_ampm == 2 && $end_hour < 11) $end_hour += 12;
							$end_date = new DateTime();
							$end_date->setDate($end_year, $end_month, $end_day);
							$end_date->setTime($end_hour, $end_minute, 0);
							
							$start_date_string = $start_date->format("Y-m-d H:i:s");
							$end_date_string = $end_date->format("Y-m-d H:i:s");
							
							if($stmt = $mysqli->prepare("INSERT INTO event (title, description, start_time, end_time, group_id, lname, zip) VALUES(?,?,?,?,?,?,?)")){
								$stmt->bind_param("ssssisi", $_POST["name"], $_POST["description"], $start_date_string, $end_date_string, $group_id, $location_name, $location_zipcode);
								$stmt->execute();
								$stmt->close();
								
								// Get new id and redirect
								if($stmt = $mysqli->prepare("SELECT max(event_id) FROM event")){
									$stmt->execute();
									$stmt->bind_result($event_id);
									if($stmt->fetch()){
										header("Location: event.php?id=".$event_id."&action=join");
									}
								}
							}
						}
					}
				
					
			
			
			
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
		
		<?php echo $actions; ?>
		
		<form action="createevent.php?id=<?php echo $group_id; ?>" method="post">
			<table cellspacing="0">
				<tr>
					<th colspan="2" class="table_header">Create New Event</th>
				</tr>
			
				<tr>
					<th>Group</th>
					<td><?php echo htmlentities($group_name); ?>
				</tr>
				
				<tr>
					<th>Event Name</th>
					<td><input name="name" <?php if(isset($_POST["name"])) echo 'value="'.$_POST["name"].'" '; ?>/></td>
				</tr>
				
				
				<tr>
					<th>Description</th>
					<td>
						<textarea name="description" style="width:100%;" rows="10"><?php if(isset($_POST["description"])) echo $_POST["description"]; ?></textarea>
					</td>
				</tr>
				
				<tr>
					<th>Start Time</th>
					<td>
						<select name="start_month">
							<?php
								$current_month = date("n");
								for($x=1;$x<=12;$x++){
									echo '<option value="'.$x.'"';
									if(isset($_POST["start_month"])){
										if($start_month == $x) echo ' selected';
									} else if($current_month == $x) echo ' selected';
									echo '>'.$MONTH[$x].'</option>';
								}
							?>
						</select>
						
						<select name="start_day">
							<?php
								$current_day = date("d");
								for($x=1;$x<=31;$x++){
									echo '<option value="'.$x.'"';
									if(isset($_POST["start_day"])){
										if($start_day == $x) echo ' selected';
									} else if($current_day == $x) echo ' selected';
									echo '>'.$x.'</option>';
								}
							?>
						</select>
						
						<!--<input name="start_day" size="2" placeholder="DD" /> / -->
						<input name="start_year" size="4" placeholder="YYYY" <?php if(isset($_POST["start_year"])) echo 'value="'.$_POST["start_year"].'" '; ?>;/> at 
						<input name="start_hour" size="2" placeholder="hh" <?php if(isset($_POST["start_hour"])) echo 'value="'.$_POST["start_hour"].'" '; ?>/>:
						<input name="start_minute" size="2" placeholder="mm" <?php if(isset($_POST["start_minute"])) echo 'value="'.$_POST["start_minute"].'" '; ?>/>
						<select name="start_ampm">
							<option value="1">AM</option>
							<option value="2"<?php if(isset($_POST["start_ampm"]) && $_POST["start_ampm"] == 2) echo ' selected'; ?>>PM</option>
						</select>
					</td>
				</tr>
				
				
				<tr>
					<th>End Time</th>
					<td>
						<select name="end_month">
							<?php
								$current_month = date("n");
								for($x=1;$x<=12;$x++){
									echo '<option value="'.$x.'"';
									if(isset($_POST["end_month"])){
										if($end_month == $x) echo ' selected';
									} else if($current_month == $x) echo ' selected';
									echo '>'.$MONTH[$x].'</option>';
								}
							?>
						</select>
						
						<select name="end_day">
							<?php
								$current_day = date("d");
								for($x=1;$x<=31;$x++){
									echo '<option value="'.$x.'"';
									if(isset($_POST["end_day"])){
										if($end_day == $x) echo ' selected';
									} else if($current_day == $x) echo ' selected';
									echo '>'.$x.'</option>';
								}
							?>
						</select>
						
						<!--<input name="start_day" size="2" placeholder="DD" /> / -->
						<input name="end_year" size="4" placeholder="YYYY" <?php if(isset($_POST["end_year"])) echo 'value="'.$_POST["end_year"].'" '; ?>/> at 
						<input name="end_hour" size="2" placeholder="hh" <?php if(isset($_POST["end_hour"])) echo 'value="'.$_POST["end_hour"].'" '; ?>/>:
						<input name="end_minute" size="2" placeholder="mm" <?php if(isset($_POST["end_minute"])) echo 'value="'.$_POST["end_minute"].'" '; ?>/>
						<select name="end_ampm">
							<option value="1">AM</option>
							<option value="2"<?php if(isset($_POST["end_ampm"]) && $_POST["end_ampm"] == 2) echo ' selected'; ?>>PM</option>
						</select>
					</td>
				</tr>
				
				
				
				<tr>
					<th>Location</th>
					<td>
						<select name="location" onchange="locationSelector(this)">
							<option value="-1">Select Location...</option>
							<?php
								if($stmt = $mysqli->prepare("SELECT lname, zip, city FROM location")){
									$stmt->execute();
									$stmt->bind_result($location_name, $location_zipcode, $location_city);
									
									while($stmt->fetch()){
										echo '<option value="'.$location_zipcode.$location_name.'"';
										if(isset($_POST["location"]) && $_POST["location"] == $location_zipcode.$location_name) echo ' selected';
										echo '>'.$location_name.' - '.$location_zipcode.', '.$location_city.'</option>';
									}
								}	
							
							?>
							<option value="other"<?php if(isset($_POST["location"]) && $_POST["location"] == "other") echo ' selected'; ?>>Add New Location...</option>
							
						</select>
					</td>
				</tr>
				
				<tr class="location_row">
					<th>Location Name</th>
					<td><input name="loc_name" size="20" maxlength="20" <?php if(isset($_POST["loc_name"])) echo 'value="'.$_POST["loc_name"].'" '; ?>/></td>
				</tr>
				
				<tr class="location_row">
					<th>Location Description</th>
					<td><textarea name="loc_description" style="width:100%;" rows="5"><?php if(isset($_POST["loc_description"])) echo $_POST["loc_description"]; ?></textarea></td>
				</tr>
				
				<tr class="location_row">
					<th>Location Street Address</th>
					<td><input name="loc_street" size="30" maxlength="50" <?php if(isset($_POST["loc_street"])) echo 'value="'.$_POST["loc_street"].'" '; ?>/></td>
				</tr>
				
				<tr class="location_row">
					<th>Location City</th>
					<td><input name="loc_city" size="20" maxlength="20" /></td>
				</tr>
				
				<tr class="location_row">
					<th>Location Zipcode</th>
					<td><input name="loc_zip" size="5" maxlength="5" /></td>
				</tr>
				
				<tr class="location_row">
					<th>Location Latitude</th>
					<td><input name="loc_latitude" size="20" /></td>
				</tr>
				
				<tr class="location_row">
					<th>Location Longitude</th>
					<td><input name="loc_longitude" size="20" /></td>
				</tr>
				
				
				<tr>
					<th colspan="2">
						<input type="submit" value="Create Event" />
					</th>
				</tr>
			</table>
		</form>
		
		<?php echo $actions; ?>
		
		<script type="text/javascript">
			function setLocationRows(n){
				var a = document.getElementsByTagName("tr");
				for(var x=0;x<a.length;x++){
					if(a[x].className == "location_row"){
						a[x].style.display = n;
					}
				}
			}
			
			function locationSelector(e){
				if(e.value == "other"){
					setLocationRows("");
				} else {
					setLocationRows("none");
				}
			}
			
			<?php if(!isset($_POST["location"]) || $_POST["location"] != "other") echo 'setLocationRows("none");'; ?>
		</script>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>

<?php
			} else $invalid = 1;
		} else $invalid = 1;
	} else $invalid = 1;
} else $invalid = 1;

if($invalid == 1){
?>

<html>
	<head>
	
		<title>Create New Event</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Error</div>
		<?php echo $actions; ?>
		<div id="main_box">
			You are not authorized to perform this action.
			<br /><br />
			<a href="groups.php">Back to List of Groups</a>
		</div>
		<?php echo $actions; ?>
		<?php include("body_footer.php"); ?>
	</body>
</html>
<?php } ?>