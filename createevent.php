<?php include("include.php");

$MONTH = array("","January","February","March","April","May","June","July","August","September","October","November","December");
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
					$stmt->bind_param("is",$group_id, $_SESSION["username"]);
					$stmt->execute();
					$stmt->bind_result($auth_user);
					$stmt->fetch();
					$stmt->close();
				}
			
				if($auth_user == 1){
				
					$actions = '<div class="actions"><a href="group.php?id='.$group_id.'">Back to Group: '.$group_name.'</a></div>';
				
					// If submitted
					if(isset($_POST["name"])){
						if($_POST["name"] == "") $errors[] = "You must enter an event name";
						
						// Verify event location
						if(strlen($_POST["location"]) < 6) $errors[] = "Invalid Location";
						else {
							$location_zipcode = substr($_POST["location"],0,5);
							$location_name = substr($_POST["location"],5);
							echo $location_zipcode." == ".$location_name;
							
							
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
				</tr>
				
				
				<tr>
					<th>End Time</th>
					<td>
						<select name="end_month">
							<?php
								$current_month = date("n");
								for($x=1;$x<=12;$x++){
									echo '<option value="'.$x.'"';
									if($current_month == $x) echo ' selected';
									echo '>'.$MONTH[$x].'</option>';
								}
							?>
						</select>
						
						<select name="end_day">
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
						<input name="end_year" size="4" placeholder="YYYY" /> at 
						<input name="end_hour" size="2" placeholder="hh" />:
						<input name="end_minute" size="2" placeholder="mm" />
						<select name="end_ampm">
							<option value="1">AM</option>
							<option value="2">PM</option>
						</select>
					</td>
				</tr>
				
				
				
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
		
		<?php echo $actions; ?>
		
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