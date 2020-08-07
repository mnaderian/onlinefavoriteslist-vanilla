<?php
	require('includes/functions.php');
	check_user_login();
	
	if(isset($_POST['category'])) {
		update_category($_SESSION['cat_id'], $_POST['category']);
	}
	
	redirect('categories.php');
?>