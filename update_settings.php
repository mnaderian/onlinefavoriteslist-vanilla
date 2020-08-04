<?php

	require('includes/functions.php');
	check_user_login();
	
	// checkbox -  open link in new window:
	if(isset($_POST['check_new_window']) && $_POST['check_new_window'] == 'new_window') {
		open_link_in_new_window(true, $_SESSION['username']);
	}
	else {
		open_link_in_new_window(false, $_SESSION['username']);
	}
	//---------------------------
	
	redirect('index.php');

?>