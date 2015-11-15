<html>
	<head>
	
		<title>Register</title>
		<!-- Include header -->
		
	</head>
	<body>
		<!-- Include body header -->
	
		<form action="register.php" method="post">
			<table>
				<tr>
					<th>Username</th>
					<td><input name="username" /></td>
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
					<td><input name="firstName" /></td>
				</tr>
				
				<tr>
					<th>Last Name</th>
					<td><input name="lastName" /></td>
				</tr>
				
				<tr>
					<th>Zipcode</th>
					<td><input name="zipcode" /></td>
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