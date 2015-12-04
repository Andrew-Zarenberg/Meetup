<?php include("include.php"); ?>

<?php
	$actions = '<div class="actions"><a href="index.php">Back to Home Page</a></div>';
?>

<html>
	<head>
	
		<title>README</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<div id="title">README</div>
		
		<?php print_errors($error, $success); ?>	
		<?php echo $actions; ?>
		
		<table cellspacing="0">
			<tr><th class="table_header">Table of Contents</th></tr>
			<tr><td>
				<strong>Table of Contents</strong><br />
				<a href="#tab1">1. Overview</a><br />
				<a href="#tab2">2. Index Page</a><br />
				<a href="#tab3">3. List of Groups</a><br />
				<a href="#tab4">4. List of Events</a><br />
				<a href="#tab5">5. List of Interests</a><br />
				<a href="#tab6">6. Group Page</a><br />
				<a href="#tab7">7. Event Page</a><br />
				<a href="#tab8">8. Interest Page</a>
			</td></tr>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab1">1. Overview</th></tr>
			<tr><td>
			
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab2">2. Index Page</th></tr>
			<tr><td>
				<strong>If not logged in:</strong><br />
				User is presented with information page.  This page contains a brief description of the Meetup application and has links to register an account or login.
				<br /><br />
				<strong>If logged in:</strong><br />
				User is shown upcoming events within the next 3 days that they have RSVP'd to.  User is also shown a list of all groups that they are a member of.
			</td></tr>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab3">3. List of Groups</th></tr>
			<tr><td>
			
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab4">4. List of Events</th></tr>
			<tr><td>
			
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab5">5. List of Interests</th></tr>
			<tr><td>
			
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab6">6. Group Page</th></tr>
			<tr><td>
			
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab7">7. Event Page</th></tr>
			<tr><td>
			
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab8">8. Interest Page</th></tr>
			<tr><td>
			
			</tr></td>
		</table><br />
		
		<?php echo $actions; ?>
		<?php include("body_footer.php"); ?>
	</body>
</html>