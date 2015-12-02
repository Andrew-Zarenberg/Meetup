<?php include("include.php"); ?>

<html>
	<head>
	
		<title>Create New Group</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<?php print_errors($error, $success); ?>
		
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
									echo '<div><input type="checkbox" name="'.$interest_name.'" /><label for="'.$interest_name.'">'.$interest_name.'</label>';
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
		
		<?php include("body_footer.php"); ?>
	</body>
</html>