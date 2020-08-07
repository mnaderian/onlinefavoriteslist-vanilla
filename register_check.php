<?php
	
	// includes
	require('includes/functions.php');
	
	// get registration information
	$username = $_POST['username'];
	$password = $_POST['password'];
	$r_password = $_POST['r_password'];
	$email = $_POST['email'];
	$registeration_successful = false;
	
	// messages: errors and success
	$_SESSION['message'] = check_register_errors($username, $password, $r_password);
	
	// sending information to the database and finishing the registration process
	// by sending success message
	if(!isset($_SESSION['message']) || $_SESSION['message'] == NULL || $_SESSION['message'] == "") {
		register($username, $password, $email);
		$registeration_successful = true;
		$_SESSION['message'] = '<h4 class="success">Registration successful, You may now 
								<a href="login.php">login</a>.</h4>';
		set_new_user_settings($username);
	}
	
	// going back to registration page with success or with errors!
	redirect('register.php');
	
	
?>