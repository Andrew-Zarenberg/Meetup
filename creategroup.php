<?php include("include.php"); ?>

<?php
	$actions = '<div class="actions"><a href="groups.php">Back to List of Groups</a></div>';
	if(isset($_SESSION["username"])){
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
									echo '<div><input type="checkbox" name="'.$interest_name.'" id="'.$interest_name.'" /><label for="'.$interest_name.'">'.$interest_name.'</label>';
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