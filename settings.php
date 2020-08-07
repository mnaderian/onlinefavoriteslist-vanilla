<?php
	
	include('includes/functions.php');
	$_SESSION['page'] = 'settings.php';
	check_user_login();
	check_user_settings($_SESSION['username']);
	$user_settings = get_user_settings($_SESSION['username']);
?>
<html>
	<head>
		<title>Settings</title>
		<link href="assets/styles/settings.css" type="text/css" rel="stylesheet" />
		<link rel="icon" type="image/png" href="assets/images/favorites.png" />
	</head>
	
	<body>
		<div id="main">
			<fieldset>
				<legend><?php echo $_SESSION['username'] . "'s "; ?>Settings</legend>
				<form id="edit_form" action="update_settings.php" method="post">
					
					<input type="checkbox" name="check_new_window" 
					<?php if($user_settings['check_new_window'] == true) 
					{echo ' checked ';} ?> value="new_window" />
					<span>Open links in a new window or tab</span><br />
					
					<div id="buttons">
						<a class="cancel" href="index.php">Cancel</a>
						<input class="submit" type="submit" name="submit_edit" value="OK" />
					</div>
				</form>
			</fieldset>
		</div>
	</body>
</html>