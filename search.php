<?php include("include.php"); ?>

<?php
	$search = htmlentities($_POST["search"]);
?>

<html>
	<head>
	
		<title><?php echo $search; ?></title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Search</div>
		<?php print_errors($error, $success); ?>
		
		<table cellspacing="0">
			<tr>
				<th class="table_header" colspan="5">Groups</th>
			</tr>
			
			<tr>
				<th>Match</th>
				<th>Name</th>
				<th>Interests</th>
				<th>Members</th>
				<th>Creator</th>
			</tr>
			
			<?php
				// 5 stars = EXACT match
				// 4 stars = GROUP% OR %GROUP
				// 3 stars = %GROUP%
				
				// because must pass by reference......
				$searchP = $search.'%';
				$Psearch = '%'.$search;
				$PsearchP = '%'.$search.'%';
				
				if($stmt = $mysqli->prepare("SELECT strength, group_id, group_name, description, username, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=results.group_id) num_members FROM ((SELECT *, 5 strength FROM `group` g WHERE g.group_name=?) UNION (SELECT *, 4 strength FROM `group` g WHERE g.group_name LIKE ? OR g.group_name LIKE ?) UNION (SELECT *, 3 strength FROM `group` g WHERE g.group_name LIKE ?)) results GROUP BY group_id")){
					$stmt->bind_param("ssss", $search, $searchP, $Psearch, $PsearchP);
					$stmt->execute();
					$stmt->bind_result($strength, $group_id, $group_name, $group_description, $group_creator, $num_members);
					$stmt->store_result();
					
					$groups = $stmt;
					while($groups->fetch()){
						echo '<tr><td>';
						
						for($x=0;$x<5;$x++){
							if($strength > $x) echo 'X';
							//else echo 'o';
							
							echo ' ';
						}
						
						echo '</td><td class="group_info"><div class="group_name"><a href="group.php?id='.$group_id.'">'.$group_name.'</a></div><div class="group_description">'.$group_description.'</div></td>';
						//echo '<td class="group_interest"><a href="interest.php?interest='.$group_interest.'">'.$group_interest.'</a></td>';
						
						echo '<td class="group_interest">';
						if($stmt = $mysqli->prepare("SELECT interest_name FROM groupinterest WHERE group_id=? ORDER BY interest_name")){
							$stmt->bind_param("i", $group_id);
							$stmt->execute();
							$stmt->bind_result($interest_name);
							while($stmt->fetch()){
								echo '<div><a href="interest.php?name='.$interest_name.'">'.$interest_name.'</a></div>';
							}
							$stmt->close();
						} else echo 'fail';
						echo '</td>';
						
						echo '<td class="group_members num">'.$num_members.'</td>';
						echo '<td class="group_creator"><a href="user.php?username='.$group_creator.'">'.$group_creator.'</a></td></tr>';
					}
					$groups->close();
				}
					
				
			?>
			
		</table>
		
		<br />
		
		<table cellspacing="0">
			<tr>
				<th class="table_header" colspan="5">Events</th>
			</tr>
			
			<tr>
				<th>Match</th>
				<th>Name</th>
				<th>Interests</th>
				<th>Members</th>
				<th>Creator</th>
			</tr>
		</table>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>