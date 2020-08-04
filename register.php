<?php 	
	require('includes/functions.php');
?>
<html>
	<head>
		<title>Registration</title>
		<link href="assets/styles/register.css" type="text/css" rel="stylesheet" />
		<link rel="icon" type="image/png" href="assets/images/favorites.png" />
	</head>
	<body>
		<div id="main">
			<?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
			<fieldset>
				<legend>Registration</legend>
				<form action="register_check.php" method="post">
					<label>Username: </label><input class="text" type="text" name="username" /><br />
					<label>Password: </label><input class="text" type="password" name="password" /><br />
					<label>Re-enter password: </label>
					<input class="text" type="password" name="r_password" /><br />
					<label>E-mail: </label><input class="text" type="text" name="email" /><br />
					<div class="btn"><a id="cancel" name="cancel" href="login.php">Cancel</a></div>
					<div class="btn"><input id="register" type="submit" value="Register" /></div>			
				</form>
			</fieldset>
		</div>
	</body>
</html>