<?php include("include.php"); ?>

<?php
	$username = "";
	
	// If no username specified, go to user's own profile page
	if(!isset($_GET["username"]) && isset($_SESSION["username"])){
		$username = $_SESSION["username"];
	} else {
		$username = $_GET["username"];
	}

	//if($stmt->mysqli_prepare("SELECT username, firstname, lastname, zipcode
?>

<html>
	<head>
	
		<title>PAGE TITLE HERE</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<?php print_errors($errors); ?>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>