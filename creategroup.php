<?php include("include.php"); ?>

<?php
	$actions = '<div class="actions"><a href="groups.php">Back to List of Groups</a></div>';
	if(isset($_SESSION["username"])){
		if(isset($_POST["name"])){
			
			if($_POST["name"] == "") $error[] = "You must enter a group name.";
			if($_POST["description"] == "") $error[] = "You must enter a description";
			
			// create group
			if($stmt = $mysqli->prepare("INSERT INTO `group` (group_name, description, username) VALUES(?,?,?)")){
				$stmt->bind_param("sss", $_POST["name"], $_POST["description"], $_SESSION["username"]);
				$stmt->execute();
				$stmt->close();
				
				// Get newly created group id and redirect to page - will be the max(group_id)
				if($stmt = $mysqli->prepare("SELECT max(group_id) FROM `group`")){
					$stmt->execute();
					$stmt->bind_result($group_id);
					if($stmt->fetch()){
					
						// Add interests - must check every interest against checkboxes
						$stmt->close();
						if($stmt = $mysqli->prepare("SELECT interest_name FROM interest")){
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result($interest_name);
							
							$interests = $stmt;
							while($interests->fetch()){
								if(isset($_POST["interest_".$interest_name])){
									if($stmt = $mysqli->prepare("INSERT INTO groupinterest VALUES(?,?)")){
										$stmt->bind_param("si",$interest_name,$group_id);
										$stmt->execute();
										$stmt->close();
									}
								}
							}
							$interests->close();
						}
									
					
					
						//$stmt->close();
						// Add used to newly created group - make authorized
						if($stmt = $mysqli->prepare("INSERT INTO groupuser VALUES(?,?,1)")){
							$stmt->bind_param("is", $group_id, $_SESSION["username"]);
							$stmt->execute();
							$stmt->close();
							
						
							header("Location: group.php?id=".$group_id);
						} else $error[] = "An error occurred adding user to group.";
					} else $error[] = "An error occurred creating new group.";
				}
			}
			
			
		}
?>

<html>
	<head>
	
		<title>Create New Group</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Create New Group</div>
		
		<?php print_errors($error, $success); ?>
		
		<?php echo $actions; ?>
		
		<form action="creategroup.php" method="post">
			<table cellspacing="0">
				<tr>
					<th colspan="2" class="table_header">Create New Group</th>
				</tr>
			
				<tr>
					<th>Group Name</th>
					<td><input name="name" size=" size="20" maxlength="20" /></td>
				</tr>
				
				<tr>
					<th>Description</th>
					<td><textarea name="description" style="width:100%;" rows="10"></textarea></td>
				</tr>
				
				<tr>
					<th>Interests</th>
					<td>
						<?php
							if($stmt = $mysqli->prepare("SELECT interest_name FROM interest ORDER BY interest_name")){
								$stmt->execute();
								$stmt->bind_result($interest_name);
								while($stmt->fetch()){
									echo '<div><input type="checkbox" name="interest_'.$interest_name.'" id="interest_'.$interest_name.'" /><label for="'.$interest_name.'">'.$interest_name.'</label>';
								}
								$stmt->close();
							}
						?>
					</td>
				</tr>
				
				<tr>
					<th colspan="2"><input type="submit" value="Create Group" /></th>
				</tr>
			</table>
		</form>
		
		<?php echo $actions; ?>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>

<?php
	} else { // If user not logged in
?>
<html>
	<head>
	
		<title>Create New Group</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Error</div>
		<?php echo $actions; ?>
		<div id="main_box">
			You must be logged in to create a new group.
			<br /><br />
			<a href="login.php">Login</a> | <a href="register.php">Register</a>
		</div>
		<?php echo $actions; ?>
		<?php include("body_footer.php"); ?>
	</body>
</html>
<?php } ?>