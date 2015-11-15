<?php include("include.php"); ?>

<?php
	$invalid = 0;
	if(isset($_GET["id"])){
		$group_id = intval($_GET["id"]);
		
		if($stmt = $mysqli->prepare("SELECT g.group_name, g.description FROM `group` g WHERE g.group_id=?")){
			$stmt->bind_param("i",$group_id);
			$stmt->execute();
			$stmt->bind_result($group_name, $group_description);
			
			if($stmt->fetch()){
			?>
			
			
			
<html>
	<head>
	
		<title><?php echo $group_name; ?></title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		
		<div id="title"><?php echo $group_name; ?>
		<?php print_errors($error, $success); ?>
		
		
		
		<?php include("body_footer.php"); ?>
	</body>
</html>
			
			
			
			<?php
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
