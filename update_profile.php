<?php

	require('includes/functions.php');
	
	check_user_login();
	
	if(isset($_POST['password']) || isset($_POST['username'])) {
		$password = $_POST['password'];
		$email = $_POST['email'];
	}
	
	$_SESSION['message'] = update_profile($_SESSION['username'],$password, $email);
	
	redirect('profile.php');

?>