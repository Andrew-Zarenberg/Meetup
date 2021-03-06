<?php include("include.php"); ?>

<?php
	if(isset($username)){
		// If already logged in cannot register
		header("Location: index.php?attemptregister=true");
	}

	if(isset($_POST["username"])){
	
		// Check if username exists
		if($_POST["username"] == "") $error[] = "You must enter a username.";
		
		if($stmt = $mysqli->prepare("SELECT username FROM member WHERE username=?")){
			$stmt->bind_param("s", $_POST["username"]);
			$stmt->execute();
			$stmt->bind_result($username_exists);
			
			if($stmt->fetch()){
				$error[] = "Username already exists.";
			}
			$stmt->close();
		}
		
		if($_POST["password1"] == "" || $_POST["password2"] == "") $error[] = "You must enter a password.";
		else if($_POST["password1"] != $_POST["password2"]) $error[] = "Password do not match.";
		
		if($_POST["firstName"] == "") $error[] = "You must enter a first name.";
		if($_POST["lastName"] == "") $error[] = "You must enter a last name.";
		
		$zipcode = intval($_POST["zipcode"]);
		if($zipcode > 99999 || $zipcode < 1) $error[] = "You must enter a valid 5-digit zipcode.";
		
		// If no errors, create user
		if(empty($error)){
			if($stmt = $mysqli->prepare("INSERT INTO member VALUES(?, ?, ?, ?, ?)")){
				$stmt->bind_param("ssssi", $_POST["username"], md5($_POST["password1"]), $_POST["firstName"], $_POST["lastName"], $zipcode);
				$stmt->execute();
				$stmt->close();
				
				// Log the new user in
				$_SESSION["username"] = $_POST["username"];
				$_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"];
				
				// Redirect
				header("Location: index.php?newuser=1");
			}
		}
				
	}
?>

<html>
	<head>
	
		<title>Register</title>
		<?php include("header.php"); ?>
		
	</head>
	<body>
		<?php include("body_header.php"); ?>
		
		<?php print_errors($error, $success); ?>
	
		<form action="register.php" method="post">
			<table cellspacing="0">
				<tr>
					<th>Username</th>
					<td><input name="username" value="<?php if(isset($_POST["username"])) echo $_POST["username"]; ?>" /></td>
				</tr>
				
				<tr>
					<th>Password</th>
					<td><input name="password1" type="password" /></td>
				</tr>
				
				<tr>
					<th>Confirm Password</th>
					<td><input name="password2" type="password" /></td>
				</tr>
				
				<tr>
					<th>First Name</th>
					<td><input name="firstName" value="<?php if(isset($_POST["firstName"])) echo $_POST["firstName"]; ?>"/></td>
				</tr>
				
				<tr>
					<th>Last Name</th>
					<td><input name="lastName" value="<?php if(isset($_POST["lastName"])) echo $_POST["lastName"]; ?>"/></td>
				</tr>
				
				<tr>
					<th>Zipcode</th>
					<td><input name="zipcode" value="<?php if(isset($_POST["zipcode"])) echo $_POST["zipcode"]; ?>"/></td>
				</tr>
				
				<tr>
					<th colspan="2" style="text-align:center;">
						<input type="submit" value="Submit" />
					</th>
				</tr>
			</table>
		</form>
		
		<?php include("body_footer.php"); ?>
	</body>
</html>