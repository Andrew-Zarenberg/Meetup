<?php include("include.php"); 


	$actions = '<div class="actions"><a href="interests.php">Back to List of Interests</a></div>';

	$invalid = 0;
	if(isset($_GET["name"])){
		
		if($stmt = $mysqli->prepare("SELECT interest_name FROM interest WHERE interest_name=?")){
			$stmt->bind_param("s",$_GET["name"]);
			$stmt->execute();
			$stmt->bind_result($interest_name);
			$stmt->store_result();
			if($stmt->fetch()){
?>

<html>
	<head>
	
		<title><?php echo htmlentities($interest_name); ?></title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title"><?php echo $interest_name; ?></div>
		
		<?php print_errors($error, $success); ?>
		
		<?php echo $actions; ?>
		
		<table cellspacing="0">
			<tr>
				<th colspan="4" class="table_header">Groups with this Interest</th>
			</tr>
			
			<tr>
				<th>Name</th>
				<th>Interests</th>
				<th>Members</th>
				<th>Creator</th>
			</tr>
			
			<?php
				//if($stmt = $mysqli->prepare('SELECT g.group_id, g.group_name, g.description, g.username, count("SELECT * FROM groupuser WHERE g.group_id=g.group_id") num_members, groupinterest.interest_name FROM `group` g, groupinterest WHERE g.group_id=groupinterest.group_id')){
				if($stmt = $mysqli->prepare("SELECT g.group_id, g.group_name, g.description, g.username, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=g.group_id) num_members FROM `group` g, groupinterest WHERE groupinterest.group_id=g.group_id AND groupinterest.interest_name=?")){
					$stmt->bind_param("s",$interest_name);
					$stmt->execute();
					$stmt->store_result();
					$stmt->bind_result($group_id, $group_name, $group_description, $group_creator, $num_members);
					
					$groups = $stmt;
					while($groups->fetch()){
						echo '<tr><td class="group_info"><div class="group_name"><a href="group.php?id='.$group_id.'">'.htmlentities($group_name).'</a></div><div class="group_description">'.htmlentities($group_description).'</div></td>';
						//echo '<td class="group_interest"><a href="interest.php?interest='.$group_interest.'">'.$group_interest.'</a></td>';
						
						echo '<td class="group_interest">';
						if($stmt = $mysqli->prepare("SELECT interest_name FROM groupinterest WHERE group_id=? ORDER BY interest_name")){
							$stmt->bind_param("i", $group_id);
							$stmt->execute();
							$stmt->bind_result($i_name);
							while($stmt->fetch()){
								echo '<div';
								if($interest_name == $i_name) echo ' style="font-weight:bold; font-style:italic;"';
								echo '><a href="interest.php?name='.htmlentities($i_name).'">'.htmlentities($i_name).'</a></div>';
							}
							$stmt->close();
						} else echo 'fail';
						echo '</td>';
						
						echo '<td class="group_members num">'.$num_members.'</td>';
						echo '<td class="group_creator"><a href="user.php?username='.htmlentities($group_creator).'">'.htmlentities($group_creator).'</a></td></tr>';
					}
					$groups->close();
				}
			?>
			
		</table>
		
		
		<?php echo $actions; ?>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>

<?php
			} else {
?>
<html>
	<head>
	
		<title>Interest Not Found</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Interest Not Found</div>
		<?php echo $actions; ?>
		<div id="main_box">
			The interest you are trying to access does not exist or has been deleted.
			<br /><br />
			<a href="interests.php">Back to List of Interests</a>
		</div>
		<?php echo $actions; ?>
		<?php include("body_footer.php"); ?>
	</body>
</html>
<?php
			}
		}
	}
?>