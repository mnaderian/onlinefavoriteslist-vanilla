<?php

	require('includes/functions.php');
	check_user_login();
	
	if(isset($_GET['id'])) {
		$user_id = $_GET['id'];
		remove_user($user_id);
		remove_list($user_id);
		remove_settings($user_id);
		redirect('logout.php');
	}
	
	redirect('profile.php');

?>