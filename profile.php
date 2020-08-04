<?php 	

	require('includes/functions.php');
	$_SESSION['page'] = 'profile.php';
	check_user_login();
	
	$profile = get_user_profile($_SESSION['username']);
	
?>
<html>
	<head>
		<title>My Profile: <?php echo $_SESSION['username']; ?></title>
		<link href="assets/styles/register.css" type="text/css" rel="stylesheet" />
		<link rel="icon" type="image/png" href="assets/images/favorites.png" />
		<style>			
			label {
			    color: red;
			}
			input.text {
			    border-color: red;
			    color: red;
			}
			a.btn {
			    cursor: default;
			    line-height: 42px;
			    text-decoration: none;
			}
			.btn {
			    background-color: red;
			    border: medium none;
			    color: white;
			    font-weight: bold;
			}
			.btn:hover {
			    border-color: red;
			    color: red;
			    width: 119px;
			    height: 37px;
			    line-height: 39px;
			}
		</style>
	</head>
	<body>
		<div id="main">
			<?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
			<fieldset style="height: 272px; border-color: red;">
				<legend style="color: red;"><?php echo $_SESSION['username'] . "'s Profile"; ?></legend>
				<form action="update_profile.php" method="post">
					<label>Username: </label>
					<input class="text" type="text" name="username" 
						value="<?php echo $profile['username']; ?>" disabled/>
					<br />
					<label>Password: </label><input class="text" type="text" name="password" 
						value="<?php echo $profile['password']; ?>"  />
					<br />
					<label>E-mail: </label><input class="text" type="text" name="email" 
						value="<?php echo $profile['email']; ?>" />
					<br />
					<a class="btn" name="cancel" href="index.php">Cancel</a>
					<input class="btn" type="submit" value="Update Profile" />
				</form>
			</fieldset>
			<div id="remove_account">
				<a href="remove_account.php?id=<?php echo $profile['id']; ?>" onclick="return confirm('Are you sure you want to delete all your information and favorites?');">
					Remove account!					
				</a>
			</div>
		</div>
	</body>
</html>