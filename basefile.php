<?php include("include.php"); ?>

<html>
	<head>
	
		<title>PAGE TITLE HERE</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<?php print_errors($error, $success); ?>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>