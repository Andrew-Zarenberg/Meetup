<?php include("include.php"); ?>

<html>
	<head>
	
		<title>Interests</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<?php print_errors($error, $success); ?>
		
		<table cellspacing="0">
			<tr>
				<th colspan="3" class="table_header">Interests</th>
			</tr>
			
			<tr>
				<th>Interest</th>
				<th># Groups</th>
				<th># Members</th>
			</tr>
			
			<?php
				if($stmt = $mysqli->prepare("SELECT interest_name FROM interest ORDER BY interest_name")){
					$stmt->execute();
					$stmt->bind_result($interest_name);
					while($stmt->fetch()){
						echo '<tr><td><a href="interest.php?name='.htmlentities($interest_name).'">'.htmlentities($interest_name).'</a></td><td>#</td><td>#</td></tr>';
					}
				}
			?>
			
		</table>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>