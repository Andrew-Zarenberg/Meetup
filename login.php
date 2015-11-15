<?php include("include.php"); ?>

<?php
	if(isset($_POST["username"])){
		if($stmt = $mysqli->prepare("SELECT username FROM member WHERE username=? AND password=?")){
			$pass = substr(md5($_POST["password"]),0, 20);
			$stmt->bind_param("ss", $_POST["username"], $pass);
			
			echo $_POST["username"]." - ".$pass;
			
			$stmt->execute();
			$stmt->bind_result($username);
			if($stmt->fetch()){
				$stmt->close();
				$_SESSION["username"] = $username;
				header("Location: index.php?loggedin=1");
			} else {
				$stmt->close();
				$errors[] = "Invalid username and password combination.";
			}
		}
	}
?>

<html>
	<head>
	
		<title>Login</title>
		<!-- Include header -->
		
	</head>
	<body>
		<!-- Include body header -->
		
		<?php print_errors($errors); ?>
		
		<form action="login.php" method="post">
			<table>
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
		
		<!-- Include body footer -->
	</body>
</html>