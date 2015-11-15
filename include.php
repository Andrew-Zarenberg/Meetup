<?php

$mysqli = new mysqli("localhost", "root", "", "meetup");

if(mysqli_connect_errno()){
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


session_start();
if(isset($SESSION["REMOTE_ADDR"]) && $SESSION["REMOTE_ADDR"] != $SERVER["REMOTE_ADDR"]) {
  session_destroy();
  session_start();
}


// Used to add error messages and display on pages
$errors = array();

function print_errors($ers){
	if(empty($ers)) return;
	echo '<div class="error"><div class="errorHeader">ERROR:</div>';
	foreach($ers as $er){
		echo "<div>" . $er . "</div>";
	}
	echo "</div>";
}
?>
