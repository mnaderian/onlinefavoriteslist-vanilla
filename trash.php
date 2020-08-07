<?php
	
	require('includes/functions.php');
	$_SESSION['page'] = 'trash.php';
	check_user_login();
		
	if(isset($_GET['restore_all']) && $_GET['restore_all'] == 1) {
		trash_restore($_SESSION['username']);
		redirect('trash.php');
	}
	if(isset($_GET['empty']) && $_GET['empty'] == 1) {
		empty_trash($_SESSION['username']);
		redirect('trash.php');
	}
	if(isset($_GET['restore'])) {
		$id = $_GET['restore'];
		trash_item_restore($id);
		redirect('trash.php');
	}
	if(isset($_GET['del'])) {
		$id = $_GET['del'];
		trash_item_delete($id);
		redirect('trash.php');
	}
	
?>
<html>
	<head>
		<title>Trash</title>
		<link href="assets/styles/trash.css" type="text/css" rel="stylesheet" />
		<link rel="icon" type="image/png" href="assets/images/favorites.png" />
	</head>
	
	<body>
		<div id="main">
			<fieldset>
				<div id="trash_count">
					<?php echo count_trashed($_SESSION['username']); ?> item(s) trashed					
				</div>
				<legend><?php echo $_SESSION['username'] . "'s "; ?>Trashed links</legend>
				<div id="trash_menu">
					<a class="menu" href="index.php">Back</a>
					<a class="menu" href="trash.php?empty=1">Empty Trash</a>
					<a class="menu" href="trash.php?restore_all=1">Restore All</a>
				</div>
				<div id="trash_list">					
					<?php
					
						if(count_trashed($_SESSION['username']) == 0) {
							echo '<p id="empty_message">Trash is empty!</p>';
						}
						else {							
							$trash_list = get_trashed_items($_SESSION['username']);
							$list_length = 70;
							while($list = mysqli_fetch_array($trash_list)) {
								$pt = $list['page_title'];
								if(strlen($pt) > $list_length) {
									$pt = substr($pt, 0, $list_length) . '...';
								}
								echo '<div class="trashed_item">
									  	<a class="url" href="'.$list['url'].'">'.$pt.'</a>
									  	<div class="options">
									  		<a href="trash.php?restore='.$list['id'].'">
									  		Restore</a><a href="trash.php?del='.$list['id'].'">
									  		Delete</a>
									  	</div>
									  </div>';
							}
						}
					
					?>				
				</div>
			</fieldset>
		</div>
	</body>
</html>