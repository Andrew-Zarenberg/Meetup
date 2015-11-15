<?php include("include.php"); ?>

<?php

	if(isset($_POST["username"])){
	
		// Check if username exists
		if($_POST["username"] == "") $errors[] = "You must enter a username.";
		
		if($stmt = $mysqli->prepare("SELECT username FROM member WHERE username=?")){
			$stmt->bind_param("s", $_POST["username"]);
			$stmt->execute();
			$stmt->bind_result($username_exists);
			
			if($stmt->fetch()){
				$errors[] = "Username already exists.";
			}
			$stmt->close();
		}
		
		if($_POST["password1"] == "" || $_POST["password2"] == "") $errors[] = "You must enter a password.";
		else if($_POST["password1"] != $_POST["password2"]) $errors[] = "Password do not match.";
		
		if($_POST["firstName"] == "") $errors[] = "You must enter a first name.";
		if($_POST["lastName"] == "") $errors[] = "You must enter a last name.";
		
		$zipcode = intval($_POST["zipcode"]);
		if($zipcode > 99999 || $zipcode < 1) $errors[] = "You must enter a valid 5-digit zipcode.";
		
		// If no errors, create user
		if(empty($errors)){
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
		<!-- Include header -->
		
	</head>
	<body>
		<!-- Include body header -->
		
		<?php
			print_errors($errors);
		?>
	
		<form action="register.php" method="post">
			<table>
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
		
		<!-- Include body footer -->
	</body>
</html>