<?php
	
	require('includes/functions.php');
	check_user_login();
	
	if(isset($_SESSION['edit_id'])) {
		$id = $_SESSION['edit_id'];
		isset($_POST['title']) ? $title = $_POST['title'] : redirect('index.php');
		isset($_POST['url']) ? $url = $_POST['url'] : redirect('index.php');
		isset($_POST['category']) ? $cat_id = $_POST['category'] : redirect('index.php');
		$sql = 'UPDATE favorites 
				SET page_title="'.$title.'", url="'.$url.'", category_id="'.$cat_id.'" 
				WHERE id="'.$id.'"';
		execute_query($sql);
	}
	
	redirect('index.php');

?>