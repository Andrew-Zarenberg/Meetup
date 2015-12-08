<?php include("include.php"); ?>

<?php
	if(isset($_GET["id"])){
		$group_id = intval($_GET["id"]);
		
		if($stmt = $mysqli->prepare("SELECT g.group_name FROM `group` g, groupuser WHERE g.group_id=groupuser.group_id AND groupuser.username=? AND groupuser.authorized=1")){
			$stmt->bind_param("s",$username);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($group_name);
			
			$ggroup = $stmt;
			if($ggroup->fetch()){
			
				if(isset($_POST["sub"])){
					if($_POST["content"] == "") $error[] = "You must enter announcement content.";
					else {
						if($stmt = $mysqli->prepare("INSERT INTO groupannouncement (group_id, username, title, announcement) VALUES(?,?,?,?)")){
							$stmt->bind_param("isss",$group_id,$username,$_POST["title"],$_POST["content"]);
							$stmt->execute();
							
							header("Location: group.php?id=".$group_id."&addannounce=true");
						}
					}
				}
			
				$actions = '<div class="actions"><a href="group.php?id='.$group_id.'">Back to Group: '.htmlentities($group_name).'</a></div>';
			?>

<html>
	<head>
	
		<title>Add Announcement</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">Add Announcement</div>
		<?php print_errors($error, $success); ?>
		
		<?php echo $actions; ?>
		<form action="createannouncement.php?id=<?php echo $group_id; ?>" method="post">
			<table cellspacing="0">
				<tr>
					<th colspan="2" class="table_header">Add Announcement</th>
				</tr>
				
				<tr>
					<th>Group Name</th>
					<td><?php echo htmlentities($group_name); ?></td>
				</tr>
				
				<tr>
					<th>Announcement Title<br /><small>(Optional)</small></th>
					<td><input name="title" size="20" maxlength="20" /></td>
				</tr>
				
				<tr>
					<th>Announcement</th>
					<td><textarea name="content" style="width:100%;" rows="10"></textarea></td>
				</tr>
				
				<tr>
					<td colspan="2" style="text-align:center;"><input type="submit" name="sub" value="Add Announcement" /></td>
				</tr>
			</table>
		</form>
		
		<?php echo $actions; ?>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>
			
			<?php
				$ggroup->close();
			} else header("Location: group.php?id=".$group_id); // if not authorized redirect to group page
		}
	}
?>