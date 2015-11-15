<?php include("include.php"); ?>

<?php
	$invalid = 0;
	if(isset($_GET["id"])){
		$group_id = intval($_GET["id"]);
		
		//if($stmt = $mysqli->prepare("SELECT g.group_name, g.description FROM `group` g WHERE g.group_id=?")){
		if($stmt = $mysqli->prepare('SELECT g.group_name, g.description, g.username, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=g.group_id) num_members, (SELECT count(*) FROM groupinterest WHERE groupinterest.group_id=g.group_id) num_interests FROM `group` g WHERE g.group_id=?')){
			$stmt->bind_param("i",$group_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($group_name, $group_description, $group_creator, $num_members, $num_interests);
			
			$group = $stmt;
			if($group->fetch()){
			
			/*
			 * START IF GROUP FOUND
			 */
			?>
			
			
			
<html>
	<head>
	
		<title><?php echo $group_name; ?></title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		
		<div id="title"><?php echo $group_name; ?></div>
		<?php print_errors($error, $success); ?>
		
		<div>
			<table cellspacing="0" class="box">
				<tr>
					<td style="width:50%;">
						<strong>Group Description:</strong><br />
						<?php echo $group_description; ?>
					</td>
					
					<td style="width:50%" rowspan="2">
						<?php
							echo '<div class="authorized"><strong>Group Creator:</strong> ';
							echo '<a href="user.php?username='.$group_creator.'">'.$group_creator.'</a>';
							echo '</div><br /><strong>Group Members: ('.$num_members.')</strong><br />';
						
							if($stmt = $mysqli->prepare("SELECT username, authorized FROM groupuser WHERE group_id=? ORDER BY authorized DESC, username")){
								$stmt->bind_param("i",$group_id);
								$stmt->execute();
								$stmt->bind_result($username, $authorized);
								while($stmt->fetch()){
									if($authorized) echo '<div class="authorized"><span>[Authorized]</span> ';
									else echo '<div>';
									echo '<a href="user.php?username='.$username.'">'.$username.'</a></div>';
								}
								$stmt->close();
							}
							
							echo '</td></tr><tr><td>';
						
							echo '<strong>Group Interests: ('.$num_interests.')</strong><br />';
							
							if($stmt = $mysqli->prepare("SELECT interest_name FROM groupinterest WHERE group_id=? ORDER BY interest_name")){
								$stmt->bind_param("i",$group_id);
								$stmt->execute();
								$stmt->bind_result($interest_name);
								while($stmt->fetch()){
									echo '<a href="interest.php?name='.$interest_name.'">'.$interest_name.'</a> &nbsp; &nbsp; &nbsp; ';
								}
								$stmt->close();
							}
						?>
					</td>
						
				</tr>
			</table>
		</div>
		
		
		
		<?php include("body_footer.php"); ?>
	</body>
</html>
			
			
			
			<?php
			/*
			 * END IF GROUP FOUND
			 */
				$group->close();
			} else $invalid = 1;
		} else $invalid = 1;
	} else $invalid = 1;
	
	if($invalid == 1){ ?>
	

<html>
	<head>
	
		<title>Group Not Found</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Group Not Found</div>
		<div id="main_box">
			The group you are trying to access does not exist or has been deleted.
			<br /><br />
			<a href="groups.php">Back to List of Groups</a>
		</div>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>
	
<?php } ?>
