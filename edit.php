<?php
	require('includes/functions.php');	
	$_SESSION['page'] = 'edit.php';
	check_user_login();
?>
<html>
	<head>
		<title>Edit</title>
		<link href="assets/styles/edit.css" type="text/css" rel="stylesheet" />
	</head>
	
	<body>
		<div id="main">
			<fieldset>
				<legend>Edit</legend>
				<form id="edit_form" action="edit_update.php" method="post">
					<?php build_edit_form(); ?>
					<label>Category: </label>
					<select name="category">
						<?php 
							if(isset($_GET['id'])) {
								print_cats_selected($_SESSION['username'], $_GET['id']);
							}
							else {
								print_options_user_categories($_SESSION['username']);
							}
						?>
					</select>
					<a class="cancel" href="index.php">Cancel</a>
					<input class="submit" type="submit" name="submit_edit" value="OK" />
				</form>
			</fieldset>
		</div>
	</body>
</html>