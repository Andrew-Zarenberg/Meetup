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
				<a href="#tabAbout">About</a><br />
				<a href="#tabOverview">Overview</a><br />
				<br /><strong>List of Files:</strong><br />
				1. <a href="#tab_body_footer">body_footer.php</a><br />
				2. <a href="#tab_body_header">body_header.php</a><br />
				3. <a href="#tab_createannouncement">createannouncement.php</a> (Create Announcement)<br />
				4. <a href="#tab_createevent">createevent.php</a> (Create Event)<br />
				5. <a href="#tab_creategroup">creategroup.php</a> (Create Group)<br />
				6. <a href="#tab_event">event.php</a> (Event Page)<br />
				7. <a href="#tab_events">events.php</a> (List of Events)<br />
				8. <a href="#tab_group">group.php</a> (Group Page)<br />
				9. <a href="#tab_groups">groups.php</a> (List of Groups)<br />
				10. <a href="#tab_header">header.php</a><br />
				11. <a href="#tab_include">include.php</a><br />
				12. <a href="#tab_index">index.php</a> (Index Page)<br />
				13. <a href="#tab_interest">interest.php</a> (Interest Page)<br />
				14. <a href="#tab_interests">interests.php</a> (List of Interests)<br />
				15. <a href="#tab_login">login.php</a> (Login)<br />
				16. <a href="#tab_logout">logout.php</a> (Logout)<br />
				17. <a href="#tab_readme">readme.php</a> (README)<br />
				18. <a href="#tab_register">register.php</a> (Register New Account)<br />
				19. <a href="#tab_search">search.php</a> (Search Results Page)<br />
				
				<!--
				<a href="#tabIndex">3. Index Page</a> (index.php)<br />
				<a href="#tabListOfGroups">4. List of Groups</a> (groups.php)<br />
				<a href="#tabListOfEvents">5. List of Events</a> (events.php)<br />
				<a href="#tabListOfInterests">6. List of Interests</a> (interests.php)<br />
				<a href="#tabGroupPage">7. Group Page</a> (group.php)<br />
				<a href="#tabEventPage">8. Event Page</a> (event.php)<br />
				<a href="#tabInterestPage">9. Interest Page</a> (interest.php)
				-->
			</td></tr>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tabAbout">About</th></tr>
			<tr><td>
				Meetup was created by Andrew Zarenberg of NYU Polytechnic School of Engineering for the course CS3083 Introduction to Databases.<br /><br />
				This code is available on <a href="https://github.com/Andrew-Zarenberg/Meetup" target="_blank">GitHub</a>
			</td></tr>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tabOverview">Overview</th></tr>
			<tr><td>
				<strong>Starting</strong><br />
				The first time the user views the application, they will be shown the <a href="#tab2">Index Page</a> which contains a brief description about Meetup and provides a link to Register Page.  After registering an account the user will be automatically logged in and redirected back to the index page.<br /><br />
				Next, the user may browse groups and events through the <a href="#tabListOfGroups">List of Groups</a> and <a href="#tabListOfEvents">List of Events</a> pages.  The user may also use the search bar.<br /><br />
				Upon viewing a group the user would like to join, the user may click the Join Group button.  Alternatively, if the user sees an event they would like to go to, they can click a link to join the group and RSVP to the event simultaneously.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_body_footer">1. body_footer.php</th></tr>
			<tr><td>
				Included at the bottom of every page's body.  Generates HTML for the footer.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_body_header">2. body_header.php</th></tr>
			<tr><td>
				Included at the top of every page's body.  Generates HTML for the header, including displaying the user's username and links to navigate with.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_createannouncement">3. createannouncement.php (Create Announcement)</th></tr>
			<tr><td>
				Checks if the user is authorized for the group and then displays a form to create an announcement.  When submitted, creates an announcement for the group.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_createevent">4. createevent.php (Create Event)</th></tr>
			<tr><td>
				Checks if the user is authorized for the group and then displays a form to create a new event.  <br /><br />
				In addition to event title and description, provides drop down menus for easy start and end date selections.  
				Authorized user may select an existing location or create a new location for the event.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_creategroup">5. creategroup.php (Create Group)</th></tr>
			<tr><td>
				Any logged in user may create a group.  User enters a group name, description, and selects from a list of checkboxes which interests to apply to the group.  Upon submission verifies the inputs, creates a new group, then redirects the user to the group page.  The user is automatically added as an authorized user.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_event">6. event.php (Event Page)</th></tr>
			<tr><td>
				Anyone may view an event page.<br /><br />
				The event name, event description, host group name, location, and list of member RSVPs are shown.  For the location, the location name, description, and address are given.<br /><br />
				
				<strong>If logged in:</strong><br />
				Users who are not a member of the host group will be provided with a link to join the group and RSVP to the event simultaneously.<br />
				Users who are a member of the host group will be provided with a link to RSVP to the event, or if they are already RSVP'd, a link to un-RSVP.<br /><br />
				
				<strong>If group creator:</strong>
				Only the group creator will have a special <em>Group Creator Actions</em> bar that allows them to delete the event.  Upon clicking the delete event link, a pop-up will ask the authorized user if they are sure they wish to delete the event.  If confirmed, the code first deletes all member RSVPs to the event, then deletes the event itself.
			
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_events">7. events.php (List of Events)</th></tr>
			<tr><td>
				Anyone may view a list of all events.<br /><br />
				For each event, the event name, description, host group, start/end dates, location, and number of RSVPs is shown.  A link is provided to go to the specific <a href="#tab_event">Event Page</a>.  Events are ordered by start date.
				<hr />
				<code>
				<strong>SQL query for fetching events:</strong><br />
				SELECT e.event_id, e.title, e.description, e.start_time, e.end_time, e.group_id, g.group_name, e.lname, e.zip, l.street, l.city, (SELECT count(*) FROM eventuser WHERE eventuser.event_id=e.event_id) num_rsvp FROM `event` e, `group` g, location l WHERE e.group_id=g.group_id AND e.lname=l.lname AND e.zip=l.zip ORDER BY e.start_time
				</code>
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_group">8. group.php (Group Page)</th></tr>
			<tr><td>
				Anyone may view a group page.<br /><br />
				The group name, description, group creator, list of interests, and list of members are shown.  For the list of members, authorized users are clearly marked.  Underneath the information is a list of all upcoming events, with a link to each <a href="#tabEventPage">Event Page</a>.<br /><br />
				
				<strong>If logged in:</strong><br />
				Users that are logged in will have a link to join the group, or if they are already a member of the group a link to leave the group.  Clicking the leave group button will prompt the user with a pop-up confirming that they wish to leave.<br /><br />
				
				<strong>If authorized user:</strong><br />
				Authorized users will have a link in at the top and bottom of the page that allows them to create a new event and to edit the group.  Clicking the edit group link takes the authorized user to a page where they may change the group name, description, and interests.<br /><br />
				
				<strong>If group creator:</strong><br />
				Only the group creator will have a special <em>Group Creator Actions</em> bar, which allows them to delete the group.  Deleting the group will delete entries from multiple SQL tables, deleting all interest entries, user memberships, RSVPs to events, events, and lastly delete its entry in the group table.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_groups">9. groups.php (List of Groups)</th></tr>
			<tr><td>
				Anyone may view a list of all groups.<br /><br />
				For each group, the group name, description, interests, number of members, and group creator is shown.  A link is provided to go to the specific <a href="#tab_group">Group Page</a>.  Groups are ordered by number of members.
				<hr />
				<code>
				<strong>SQL query for fetching groups:</strong><br />
				SELECT g.group_id, g.group_name, g.description, g.username, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=g.group_id) num_members FROM `group` g ORDER BY num_members DESC
				</code>
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_header">10. header.php</th></tr>
			<tr><td>
				Included in the head tag in every page.  Mainly used for just outputting the CSS styling.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_include">11. include.php</th></tr>
			<tr><td>
				The absolute first thing included in every single file.  Connects to the database, verifies user session, and contains a few functions.
			</tr></td>
		</table><br />
		
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_index">12. index.php (Index Page)</th></tr>
			<tr><td>
				<strong>If not logged in:</strong><br />
				User is presented with information page.  This page contains a brief description of the Meetup application and has links to register an account or login.
				<br /><br />
				<strong>If logged in:</strong><br />
				User is shown upcoming events within the next 3 days that they have RSVP'd to.  User is also shown a list of all groups that they are a member of.
			</td></tr>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_interests">13. interest.php (Interest Page)</th></tr>
			<tr><td>
				Anyone may view an interest page.<br /><br />
				A list of all groups that have this interest are displayed.  For each group, the group name, description, list of interests, number of members, and group creator are shown.  A link to the <a href="#tabGroupPage">Group Page</a> is given as well.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_interests">14. interests.php (List of Interests)</th></tr>
			<tr><td>
				Anyone may view a list of all interests.<br /><br />
				For each interest, the interest name, number of groups with that interest, and number of members with that interest are shown.  A link is provided to go to the specific <a href="#tabInterestPage">Interest Page</a>.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_login">15. login.php (Login)</th></tr>
			<tr><td>
				Presents a non-logged in user a form to enter their username and password.  When submitted, checks if it is a valid login, sets session variables, and redirects to the homepage.  If invalid login, generates error and prompts user to retry logging in.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_logout">16. logout.php (Logout)</th></tr>
			<tr><td>
				Logs out a user and redirects to homepage.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_readme">17. readme.php (README)</th></tr>
			<tr><td>
				Contains all of this information about each file and how to use Meetup that you are currently reading.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_register">18. register.php (Register New Account)</th></tr>
			<tr><td>
				Displays a form for a non-logged in user to create a new account.  Upon form submission, verifies that the username does not already exist, two passwords match, and checks for a valid 5-digit zipcode.
			</tr></td>
		</table><br />
		
		<table cellspacing="0">
			<tr><th class="table_header" id="tab_search">19. search.php (Search Results Page)</th></tr>
			<tr><td>
				Upon submission of the search located at the top of every page, user will be brought to this page to display search results for groups and events.<br /><br />
				Each match contains a match strength, which is either 5 stars, 4 stars, or 3 stars.  An <em>exact</em> match is 5 stars.  If the result <em>starts</em> or <em>ends</em> with the search, 4 stars.  If the result <em>contains</em> the search anywhere in the name, 3 stars.
				<hr />
				<code>
				<strong>SQL query for groups</strong> (<em>? = search query</em>)<br />
				SELECT strength, group_id, group_name, description, username, (SELECT count(*) FROM groupuser WHERE groupuser.group_id=results.group_id) num_members FROM ((SELECT *, 5 strength FROM `group` g WHERE g.group_name=?) UNION (SELECT *, 4 strength FROM `group` g WHERE g.group_name LIKE ? OR g.group_name LIKE ?) UNION (SELECT *, 3 strength FROM `group` g WHERE g.group_name LIKE ?)) results GROUP BY group_id ORDER BY strength DESC
				
				<br /><br />
				<strong>SQL query for events</strong> (<em>? = search query</em>)<br />
				SELECT strength, e.event_id, e.title, e.description, e.start_time, e.end_time, e.group_id, g.group_name, e.lname, e.zip, l.street, l.city, (SELECT count(*) FROM eventuser WHERE eventuser.event_id=e.event_id) num_rsvp FROM ((SELECT *, 5 strength FROM `event` e WHERE e.title=?) UNION (SELECT *, 4 strength FROM `event` e WHERE e.title LIKE ? OR e.title LIKE ?) UNION (SELECT *, 3 strength FROM `event` e WHERE e.title LIKE ?)) e, `group` g, location l WHERE e.group_id=g.group_id AND e.lname=l.lname AND e.zip=l.zip GROUP BY event_id ORDER BY strength DESC
				</code>
			</tr></td>
		</table><br />
		
		<?php echo $actions; ?>
		<?php include("body_footer.php"); ?>
	</body>
</html>