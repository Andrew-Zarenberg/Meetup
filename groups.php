<?php include("include.php"); ?>

<html>
	<head>
	
		<title>Groups</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<!-- Include body header -->
		<?php include("body_header.php"); ?>
		
		<?php print_errors($errors); ?>
		
		<table cellspacing="0">
			<tr>
				<th>Name</th>
				<th>Interest</th>
				<th>Members</th>
				<th>Creator</th>
			</tr>
			
			<?php
				if($stmt = $mysqli->prepare('SELECT g.group_id, g.group_name, g.description, g.username, count("SELECT * FROM groupuser WHERE g.group_id=g.group_id") num_members, groupinterest.interest_name FROM `group` g, groupinterest')){
					$stmt->execute();
					$stmt->bind_result($group_id, $group_name, $group_description, $group_creator, $num_members, $group_interest);
					while($stmt->fetch()){
						echo '<tr><td><div class="group_name"><a href="group.php?id='.$group_id.'">'.$group_name.'</a></div><div class="group_description">'.$group_description.'</div></td>';
						echo '<td>'.$group_interest.'</td>';
						echo '<td>'.$num_members.'</td>';
						echo '<td><a href="user.php?username='.$group_creator.'">'.$group_creator.'</a></td></tr>';
					}
					$stmt->close();
				}
			?>
			
		</table>
		
		<!-- Include body footer -->
		<?php include("body_footer.php"); ?>
	</body>
</html>