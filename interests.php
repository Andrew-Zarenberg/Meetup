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
				<th colspan="2" class="table_header">Interests</th>
			</tr>
			
			<tr>
				<th>Interest</th>
				<th># Groups With Interest</th>
				<!--<th># Members With Interest</th>-->
			</tr>
			
			<?php
				if($stmt = $mysqli->prepare("SELECT interest_name, (SELECT count(*) FROM groupinterest WHERE groupinterest.interest_name=interest.interest_name) num_groups FROM interest ORDER BY interest_name")){
					$stmt->execute();
					$stmt->bind_result($interest_name, $num_groups);//, $num_members);
					while($stmt->fetch()){
						echo '<tr><td><a href="interest.php?name='.htmlentities($interest_name).'">'.htmlentities($interest_name).'</a></td><td class="num">'.$num_groups.'</td></tr>';//<td class="num">'.$num_members.'</td></tr>';
					}
				}
			?>
			
		</table>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>