<?php

	require('includes/functions.php');
	check_user_login();
	
	if(isset($_POST['choice'])) {
		print_r($_POST['choice']);
	}
	else {
		echo 'nothing isset!';
	}

?>