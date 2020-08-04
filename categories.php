<?php
	require('includes/functions.php');
	$_SESSION['page'] = 'categories.php';
	check_user_login();
	
	if(isset($_GET['delete_links'])) {
		$user_id = get_user_id($_SESSION['username']);
		del_category_links($_GET['delete_links'], $user_id);
		redirect('categories.php');
	}
	
	if(isset($_GET['remove'])) {
		remove_category($_GET['remove'], $_SESSION['username']);
		redirect('categories.php');
	}
	
	if(isset($_POST['category'])) {
		add_new_category($_POST['category'], $_SESSION['username']);
	}
?>
<html>
	<head>
		<title>Categories</title>
		<link href="assets/styles/categories.css" type="text/css" rel="stylesheet" />
		<link rel="icon" type="image/png" href="assets/images/favorites.png" />
	</head>
	
	<body>
		<div id="main">
			<fieldset>
				<legend>Add Category</legend>
				<form name="add_category" method="post" action="categories.php">
					<label>Category: </label>
					<input type="text" class="text" name="category" />
					<input type="submit" class="submit" value="Add Category" />
					<?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
				</form>
			</fieldset>
			<fieldset id="cat_list">
				<div id="trash_count">Categories Count: 
					<?php echo count_cats($_SESSION['username']); ?></div>
				<legend><?php echo $_SESSION['username']; ?>'s Categories</legend>
				<div id="trash_menu">
					<a class="menu" href="index.php">Back</a>
				</div>
				<div id="trash_list">
					<?php
						$user_cats = get_user_categories($_SESSION['username']);
						while($cats = mysqli_fetch_array($user_cats)) {
							$cat_name = $cats['category_name'];
							$cat_length = 43;
							echo '<div class="trashed_item">';
							if(strlen($cat_name) > $cat_length) {
								 $cat_name = substr($cat_name, 0, $cat_length) . '...';
							}
							echo '<span>'.$cat_name.'<i>';
							echo count_category_links($cats['id'], $_SESSION['username']);
							echo ' links</i></span>';
							if(!($cats['category_name'] == 'Main')){
								echo '<a href="edit_category.php?id='.$cats['id'].'">Edit</a>
									  <a href="categories.php?remove='.$cats['id'].'">Remove</a>
									  ';
							}
							echo '<a href="categories.php?delete_links='.$cats['id'].'">Delete Links</a>';
							echo '</div>';
						}
					?>
					
				</div>
			</fieldset>
		</div>
	</body>
</html>