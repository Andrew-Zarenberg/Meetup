<?php include("include.php"); ?>

<html>
	<head>
	
		<title>Groups</title>
		<!-- Include header -->
		
	</head>
	<body>
		<!-- Include body header -->
		
		<?php print_errors($errors); ?>
		
		<table>
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Creator</th>
			</tr>
			
			<?php
				if($stmt = $mysqli->prepare("SELECT * FROM group ORDER BY name")){
					$stmt->execute();
					$stmt->bind_results($group_id, $group_name, $group_description, $group_creator);
					
					while($stmt->fetch()){
						echo '<tr><td><div class="group_name"><a href="group.php?id='.$group_id.'">'.$group_name.'</a></div><div class="group_description">'.$group_description.'</div></td>';
						echo '<td><a href="user.php?username='.$group_creator.'">'.$group_creator.'</a></td></tr>';
					}
				}
			?>
			
		</table>
		
		<!-- Include body footer -->
	</body>
</html>