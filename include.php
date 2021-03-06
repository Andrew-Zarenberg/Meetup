<?php

$EVENT_DATE_FORMAT = "l n/j/Y g:i A";

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


// check session
if(isset($_SESSION["username"])){
	if($stmt = $mysqli->prepare("SELECT username FROM member WHERE username=?")){
		$stmt->bind_param("s",$_SESSION["username"]);
		$stmt->execute();
		$stmt->bind_result($us);
		if($stmt->fetch()){
			$username = $us; // bind result so proper capitalization
		}
		$stmt->close();
	}
}
		


// Used to add error messages and display on pages
$error = array();
$success = array();

function print_errors($ers, $ses){
	if(!empty($ers)){
		echo '<div class="error"><div class="error_head">ERROR:</div>';
		foreach($ers as $er){
			echo "<div>" . $er . "</div>";
		}
		echo "</div>";
	}
	
	if(!empty($ses)){
		echo '<div class="success"><div class="success_head">SUCCESS:</div>';
		foreach($ses as $se){
			echo "<div>".$se."</div>";
		}
		echo "</div>";
	}
}

?>
