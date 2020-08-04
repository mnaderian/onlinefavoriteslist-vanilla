<?php
	require('includes/functions.php');
	$_SESSION['page'] = 'edit_category.php';
	check_user_login();
	
	if(isset($_GET['id'])) {
		$cat_id = $_GET['id'];
		compare_by_main_category($cat_id, $_SESSION['username']);
		compare_by_all_main_cats($cat_id);
		$cat_name = get_category_name($cat_id, $_SESSION['username']);
		$_SESSION['cat_id'] = $_GET['id'];
	}
?>
<html>
	<head>
		<title>Edit Category</title>
		<link href="assets/styles/edit_category.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div id="main">			
			<fieldset>
				<?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
				<legend>Edit Category</legend>
				<form id="category_edit" method="post" action="update_category.php">
					<label>Category: </label>
					<input type="text" class="text" name="category" value="<?php echo $cat_name; ?>" />
					<input type="submit" class="submit" Value="Update Category" />
					<a class="cancel" href="categories.php">Cancel</a>
				</form>
			</fieldset>
		</div>
	</body>
</html>