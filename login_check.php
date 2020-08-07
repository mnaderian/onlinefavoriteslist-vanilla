<?php

	require('includes/functions.php');
	
	// get information
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	// check for any errors
	$_SESSION['message'] = check_login_errors($username, $password);
	
	// set session username
	if(!isset($_SESSION['message']) || $_SESSION['message'] == NULL || $_SESSION['message'] == "") {
		$_SESSION['username'] = $username;
	}
	
	// redirect to index if successful!
	// interestingly it will redirect itself to login page if
	// login isn't successful!
	if(isset($_SESSION['page']) || !empty($_SESSION['page'])) {
		redirect($_SESSION['page']);
	}
	else {
		redirect('index.php');
	}
	
?>