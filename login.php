<?php include("include.php"); ?>

<?php
	if(isset($username)){
		// If already logged in cannot login again
		header("Location: index.php?attemptlogin=true");
	}
	
	
	if(isset($_POST["username"])){
		if($stmt = $mysqli->prepare("SELECT username FROM member WHERE username=? AND password=?")){
			$pass = md5($_POST["password"]);
			$stmt->bind_param("ss", $_POST["username"], $pass);
			
			$stmt->execute();
			$stmt->bind_result($username);
			if($stmt->fetch()){
				$stmt->close();
				$_SESSION["username"] = $username;
				header("Location: index.php?loggedin=1");
			} else {
				$stmt->close();
				$error[] = "Invalid username and password combination.";
			}
		}
	}
?>

<html>
	<head>
	
		<title>Login</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<?php print_errors($error, $success); ?>
		
		<form action="login.php" method="post">
			<table cellspacing="0">
				<tr>
					<th>Username</th>
					<td><input name="username" /></td>
				</tr>
				
				<tr>
					<th>Password</th>
					<td><input name="password" type="password" /></td>
				</tr>
				
				<tr>
					<th colspan="2" style="text-align:center;">
						<input type="submit" value="Login" />
					</th>
				</tr>
			</table>
		</form>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>