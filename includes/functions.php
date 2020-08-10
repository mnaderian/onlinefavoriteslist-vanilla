<?php

//  includes and requires -----------------------------
require_once('database_info.php');
require('session.php');
//  ---------------------------------------------------

// turn off error reporting
error_reporting(0);

//  variables -----------------------------------------
$connection = new mysqli(SERVER, USER, PASS, DBNAME);
if (!$connection) {
	die('Mysql connection error: ' . $connection->error);
}

// page variables
$index_title = 'Online Favorites List';
$app_version = '2.0';
//  ---------------------------------------------------

//	user login ----------------------------------------
function check_user_login()
{
	if (!logged_in()) {
		redirect('login.php');
	}
}

function logged_in()
{
	if (isset($_SESSION['username'])) {
		return true;
	} else {
		return false;
	}
}

function redirect($address)
{
	if (isset($address)) {
		header("Location: " . $address);
	}
	echo "redirection error. check your redirection address.";
}
//  ---------------------------------------------------

//	General functions ---------------------------------
function execute_query($sql)
{
	global $connection;
	$result = mysqli_query($connection, $sql);
	// check_query_error($result);
	return $result;
}

function get_user_id($username)
{
	global $connection;
	$sql = 'SELECT id FROM users WHERE username = "' . $username . '"';
	$result = mysqli_query($connection, $sql);
	$user_id = mysqli_fetch_array($result);
	return $user_id['id'];
}

function check_query_error($input)
{
	global $connection;

	if (!$input) {
		die('MySQL query error: ' . $connection->error);
	}
}
//	---------------------------------------------------

//  Main functions ------------------------------------
function add_site($url, $cat_id)
{
	if (isset($url)) {
		$url = check_url_http($url);  // checks if the given url starts with http
		$username = $_SESSION['username'];
		$user_id = get_user_id($username);
		$page_title = get_page_title($url);
		$sql = 'INSERT INTO favorites (user_id, category_id, page_title, url) 
			        VALUES ("' . $user_id . '", ' . $cat_id . ', "' . $page_title . '", "' . $url . '")';
		$result = execute_query($sql);
	}
	return $result;
}

function check_url_http($url)
{
	$str = substr($url, 0, 4);
	if (!($str == "http")) {
		return 'http://' . $url;
	} else {
		return $url;
	}
}

function get_page_title($url)
{
	$str = file_get_contents($url);
	if (strlen($str) > 0) {
		preg_match("/\<title\>(.*)\<\/title\>/", $str, $title);
		if ($title[1] == "") {
			preg_match("/\<TITLE\>(.*)\<\/TITLE\>/", $str, $title);
		}
		return $title[1];
	}
}

function get_favorites_list($user_id)
{
	global $connection;
	if (isset($_SESSION['selected_cat'])) {
		$cat_id = $_SESSION['selected_cat'];
		$sql = 'SELECT * 
					FROM favorites 
					WHERE user_id = "' . $user_id . '" 
					AND category_id = ' . $cat_id . ' 
					AND trashed = 0';
	} else {
		$sql = 'SELECT * 
					FROM favorites 
					WHERE user_id = "' . $user_id . '" 
					AND trashed = 0';
	}
	return execute_query($sql);
}

function print_favorites_list($list, $username)
{
	$settings = get_user_settings($username);
	while ($fav_list = mysqli_fetch_array($list)) {
		$title = $fav_list['page_title'];
		if (empty($title)) {
			$title = '[ unknown title ]';
		}
		$title_length = 95;
		echo '<div class="link">';
		echo '<a href="' . $fav_list['url'] . '" title="' . $title . '"';
		if ($settings['check_new_window'] == true) {
			echo ' target="_blank" ';
		}
		if (strlen($title) > $title_length) {
			$title = substr($title, 0, $title_length) . '...';
		}
		echo '>' . $title . '</a></div><div class="options"><a href="';
		echo 'index.php?del=' . $fav_list['id'] . '">Delete</a>&nbsp;&nbsp;<a href="';
		echo 'edit.php?id=' . $fav_list['id'] . '">Edit</a></div><br />';
	}
}

function delete_fav($id)
{
	global $connection;
	$sql = 'UPDATE favorites SET trashed = 1 WHERE id = ' . $id;
	$result = mysqli_query($connection, $sql);
	// check_query_error($result);
	redirect('index.php');
}

function clear_list($username)
{
	$user_id = get_user_id($username);
	if (isset($_SESSION['selected_cat'])) {
		$sql = 'UPDATE favorites 
					SET trashed = 1 
					WHERE user_id = "' . $user_id . '" 
					AND category_id = "' . $_SESSION['selected_cat'] . '"';
	} else {
		$sql = 'UPDATE favorites SET trashed = 1 WHERE user_id = ' . $user_id;
	}
	execute_query($sql);
	redirect('index.php');
}

function number_of_favorites($username)
{
	global $connection;
	$user_id = get_user_id($username);
	if (isset($_SESSION['selected_cat'])) {
		$sql = 'SELECT COUNT(user_id) 
					FROM favorites 
					WHERE user_id="' . $user_id . '" 
					AND category_id = ' . $_SESSION['selected_cat'] . ' 
					AND trashed = 0';
	} else {
		$sql = 'SELECT COUNT(user_id) FROM favorites WHERE user_id="' . $user_id . '" AND trashed = 0';
	}
	$sql_result = mysqli_query($connection, $sql);
	// check_query_error($sql_result);
	$result = mysqli_fetch_array($sql_result);
	return $result['COUNT(user_id)'];
}
//	--------------------------------------

//	registration functions ----------------
function register($username, $password, $email)
{
	$sql = 'INSERT INTO users (username, password, email)
			  	VALUES ("' . $username . '", "' . $password . '", "' . $email . '")';
	execute_query($sql);
	$user_id = get_user_id($username);
	$sql = 'INSERT INTO categories (user_id, category_name) VALUES (' . $user_id . ', Main)';
	execute_query($sql);
	set_main_category($user_id);
}
//	---------------------------------------

//	edit functions -------------------------
function build_edit_form()
{
	global $_GET;
	if (isset($_GET['id'])) {
		$_SESSION['edit_id'] = $_GET['id'];
		$sql = 'SELECT * FROM favorites WHERE id="' . $_GET['id'] . '"';
		$result = execute_query($sql);
		// check_query_error($result);
		$edit_info = mysqli_fetch_array($result);
		echo '<label>Title: </label><input class="text" type="text" name="title" 
			      value="' . $edit_info['page_title'] . '" /><br />
				  <label>URL: </label><input class="text" type="text" name="url" 
				  value="' . $edit_info['url'] . '" /><br />';
	} else {
		echo '<label>Title: </label>
				  <input class="text" type="text" name="title" /><br />
				  <label>URL: </label>
				  <input class="text" type="text" name="url" /><br />
				  <label>';
	}
}
//	----------------------------------------

//	user profile functions----------------
function get_user_profile($username)
{
	global $connection;
	$sql = 'SELECT * FROM users WHERE username = "' . $username . '"';
	$result = execute_query($sql);
	// check_query_error($result);
	return mysqli_fetch_array($result);
}

function update_profile($username, $password, $email)
{
	global $connection;
	if (empty($password) || $password == " ") {
		return '<h4 class="error"><b>error:</b> Profile update failed.</h4>';
	}
	$id = get_user_id($username);
	$sql = 'UPDATE users SET password="' . $password . '", email="' . $email . '" 
			    WHERE id="' . $id . '"';
	$result = mysqli_query($connection, $sql);
	// check_query_error($result);
	return '<h4 class="success">Profile updated successfully. 
				<a href="index.php">back to favorites list</a></h4>';
}

function remove_user($id)
{
	$sql = 'DELETE FROM users WHERE id = "' . $id . '"';
	execute_query($sql);
	remove_user_categories($id);
}

function remove_user_categories($user_id)
{
	$sql = 'DELETE FROM categories WHERE user_id = "' . $user_id . '"';
	execute_query($sql);
}

function remove_list($user_id)
{
	global $connection;
	$sql = 'DELETE FROM favorites WHERE user_id = "' . $user_id . '"';
	$result = $connection->query($connection, $sql);
	// check_query_error($result);
}
//  --------------------------------------

//	user categories functions-------------
function check_user_categories($user_id)
{
	$sql = 'SELECT * FROM categories WHERE user_id = "' . $user_id . '"';
	$result = mysqli_fetch_array(execute_query($sql));
	if (empty($result)) {
		set_main_category($user_id);
	}
}

function set_main_category($user_id)
{
	$sql = 'INSERT INTO categories (user_id, category_name) 
				VALUES (' . $user_id . ', "Main")';
	execute_query($sql);
}

function get_user_categories($username)
{
	$user_id = get_user_id($username);
	check_user_categories($user_id);
	$sql = 'SELECT * FROM categories WHERE user_id = "' . $user_id . '" ';
	$result = execute_query($sql);
	return $result;
}

function print_options_user_categories($username)
{
	$cats = get_user_categories($username);
	while ($cat = mysqli_fetch_array($cats)) {
		if (!($cat['category_name'] == 'Main')) {
			echo "\t\t\t\t\t\t";
		}
		echo '<option value="' . $cat['id'] . '">' . $cat['category_name'] . '</option>' . "\n";
	}
}

function print_options_user_categories_selected($username)
{
	$cats = get_user_categories($username);
	if (isset($_SESSION['selected_cat'])) {
		$cat_id = $_SESSION['selected_cat'];
	}
	while ($cat = mysqli_fetch_array($cats)) {
		if (!($cat['category_name'] == 'Main')) {
			echo "\t\t\t\t\t\t";
		}
		echo '<option value="' . $cat['id'] . '"';
		if ($cat['id'] == $cat_id) {
			echo ' selected';
		}
		echo '>' . $cat['category_name'] . '</option>' . "\n";
	}
}

function get_main_category_id($username)
{
	$user_id = get_user_id($username);
	$sql = 'SELECT id FROM categories WHERE user_id="' . $user_id . '" AND category_name="Main"';
	$result = mysqli_fetch_array(execute_query($sql));
	return $result['id'];
}

function print_cats_selected($username, $id)
{
	$cats = get_user_categories($username);
	if (isset($id)) {
		$cat_id = get_favorite_category($id);
	}
	while ($cat = mysqli_fetch_array($cats)) {
		if (!($cat['category_name'] == 'Main')) {
			echo "\t\t\t\t\t\t";
		}
		echo '<option value="' . $cat['id'] . '"';
		if ($cat['id'] == $cat_id) {
			echo ' selected';
		}
		echo '>' . $cat['category_name'] . '</option>' . "\n";
	}
}

function get_favorite_category($id)
{
	$sql = 'SELECT category_id FROM favorites WHERE id=' . $id;
	$result = execute_query($sql);
	$result = mysqli_fetch_array($result);
	return $result['category_id'];
}

function count_cats($username)
{
	$user_id = get_user_id($username);
	$sql = 'SELECT COUNT(*) FROM categories WHERE user_id = ' . $user_id;
	$result = mysqli_fetch_array(execute_query($sql));
	return $result['COUNT(*)'];
}

function count_category_links($cat_id, $username)
{
	$user_id = get_user_id($username);
	$sql = 'SELECT COUNT(*) FROM favorites WHERE user_id = "' . $user_id . '"
			AND category_id = "' . $cat_id . '" AND trashed = 0';
	$result = mysqli_fetch_array(execute_query($sql));
	return $result['COUNT(*)'];
}

function del_category_links($cat_id, $user_id)
{
	$sql = 'UPDATE favorites SET trashed = 1 WHERE user_id = "' . $user_id . '" 
				AND category_id = ' . $cat_id;
	$result = execute_query($sql);
}

function remove_category($cat_id, $username)
{
	if ($cat_id == get_main_category_id($username)) {
		return;
	} else {
		$sql = 'DELETE 
					FROM categories 
					WHERE id = "' . $cat_id . '" 
					AND user_id = ' . get_user_id($username);
		$result = execute_query($sql);
		$sql = 'UPDATE favorites 
					SET category_id = ' . get_main_category_id($username) . ' 
					WHERE category_id = ' . $cat_id . ' AND user_id = ' . get_user_id($username);
		$result2 = execute_query($sql);
	}
}

function add_new_category($cat_name, $username)
{
	$cat_name = trim($cat_name);
	$user_id = get_user_id($username);
	if ($cat_name == 'Main' || $cat_name == 'main') {
		$_SESSION['error'] = '<span class="error">don\'t name any category as Main.</span>';
		return;
	}
	if (category_exists($cat_name, $username)) {
		$_SESSION['error'] = '<span class="error">The category already exists.</span>';
		return;
	}
	$sql = 'INSERT INTO categories (user_id, category_name) 
				VALUES (' . $user_id . ', "' . $cat_name . '")';
	$result = execute_query($sql);
}

function category_exists($cat_name, $username)
{
	$user_id = get_user_id($username);
	$sql = 'SELECT * FROM categories 
				WHERE user_id = "' . $user_id . '" AND category_name = "' . $cat_name . '"';
	$result = mysqli_fetch_array(execute_query($sql));
	if (empty($result)) {
		return false;
	} else {
		return true;
	}
}

function compare_by_main_category($cat_id, $username)
{
	$main_category = get_main_category_id($username);
	if ($cat_id == $main_category) {
		redirect('categories.php');
	}
}

function compare_by_all_main_cats($cat_id)
{
	$sql = 'SELECT * FROM categories';
	$result = execute_query($sql);
	while ($cats = mysqli_fetch_array($result)) {
		if ($cats['category_name'] == 'Main' && $cats['id'] == $cat_id) {
			redirect('categories.php');
		}
	}
}

function get_category_name($cat_id, $username)
{
	$user_id = get_user_id($username);
	$sql = 'SELECT * FROM categories WHERE id = ' . $cat_id;
	$result = mysqli_fetch_array(execute_query($sql));
	return $result['category_name'];
}

function update_category($cat_id, $cat_name)
{
	if (!empty($cat_name)) {
		$sql = 'UPDATE categories SET category_name = "' . $cat_name . '" 
			        WHERE id = ' . $cat_id;
		$result = execute_query($sql);
	}
}
//	--------------------------------------

//	trash functions-----------------------
function get_trashed_items($username)
{
	global $connection;
	$user_id = get_user_id($username);
	$sql = 'SELECT * FROM favorites WHERE user_id = "' . $user_id . '" AND trashed = 1';
	$list = mysqli_query($connection, $sql);
	// check_query_error($list);
	return $list;
}

function trash_restore($username)
{
	global $connection;
	$user_id = get_user_id($username);
	$sql = 'UPDATE favorites SET trashed = 0 WHERE user_id = ' . $user_id . ' AND trashed = 1';
	$result = mysqli_query($connection, $sql);
	// check_query_error($result);
}

function empty_trash($username)
{
	global $connection;
	$user_id = get_user_id($username);
	$sql = 'DELETE FROM favorites WHERE user_id = "' . $user_id . '" AND trashed = 1';
	$result = mysqli_query($connection, $sql);
	// check_query_error($result);
}

function trash_item_restore($id)
{
	global $connection;
	if (isset($id)) {
		$sql = 'UPDATE favorites SET trashed = 0 WHERE id = ' . $id;
		$result = mysqli_query($connection, $sql);
		// check_query_error($result);
	}
}

function trash_item_delete($id)
{
	global $connection;
	$sql = 'DELETE FROM favorites WHERE id = ' . $id;
	$result = mysqli_query($connection, $sql);
	// check_query_error($result);
}

function count_trashed($username)
{
	global $connection;
	$user_id = get_user_id($username);
	$sql = 'SELECT COUNT(*) FROM favorites WHERE user_id = ' . $user_id . ' AND trashed = 1';
	$sql_result = mysqli_query($connection, $sql);
	// check_query_error($sql_result);
	$result = mysqli_fetch_array($sql_result);
	return $result['COUNT(*)'];
}
//	--------------------------------------

//  settings management-------------------
function check_user_settings($username)
{
	$user_id = get_user_id($username);
	$sql = 'SELECT * FROM user_settings WHERE user_id = "' . $user_id . '"';
	$result = execute_query($sql);
	$settings = mysqli_fetch_array($result);
	if (empty($settings) || $settings == NULL) {
		set_new_user_settings($username);
	}
}

function set_new_user_settings($username)
{
	$user_id = get_user_id($username);
	$check_new_window = false;
	$choice = 0;
	$sql = 'INSERT INTO user_settings VALUES ("' . $user_id . '", "' . $check_new_window . '", 0)';
	$result = execute_query($sql);
	return $result;
}

function remove_settings($user_id)
{
	global $connection;
	$sql = 'DELETE FROM user_settings WHERE user_id="' . $user_id . '"';
	execute_query($sql);
}

function open_link_in_new_window($option, $username)
{
	$user_id = get_user_id($username);
	$sql = 'UPDATE user_settings SET check_new_window="' . $option . '" WHERE user_id="' . $user_id . '"';
	execute_query($sql);
}

function get_user_settings($username)
{
	global $connection;
	$user_id = get_user_id($username);
	$sql = 'SELECT * FROM user_settings WHERE user_id="' . $user_id . '"';
	return mysqli_fetch_array(mysqli_query($connection, $sql));
}

function set_choices($option, $username)
{
	global $connection;
	$user_id = get_user_id($username);
	$sql = 'UPDATE user_settings SET choices = "' . $option . '" WHERE user_id = "' . $user_id . '"';
	execute_query($sql);
}
//  ---------------------------------------	

//  error handling-------------------------
function check_login_errors($username, $password)
{
	$errors = "";
	if (empty($username) || $username == " ") {
		$errors = '<h4 class="error"><b>error: </b>enter your username, please!</h4>';
	}
	if (!username_exists($username)) {
		$errors .= '<h4 class="error"><b>error: </b>username not valid.</h4>';
	}
	if (empty($password) || $password == " ") {
		$errors .= '<h4 class="error"><b>error: </b>enter your password, please!</h4>';
	}
	if (username_exists($username) && !check_password($password, $username)) {
		$errors .= '<h4 class="error"><b>error: </b>password incorrect.</h4>';;
	}
	return $errors;
}

function check_password($password, $username)
{
	global $connection;
	$sql = 'SELECT password 
			    FROM users 
			    WHERE password="' . $password . '" AND username="' . $username . '"';
	$check_pass = mysqli_fetch_array(mysqli_query($connection, $sql));
	if ($check_pass == "") {
		return false;
	} else {
		return true;
	}
}

function check_register_errors($username, $password, $r_password)
{
	$errors = "";
	if (empty($username) || $username == " ") {
		$errors = '<h4 class="error"><b>error:</b> Please enter your username.</h4>';
	}
	if (username_exists($username)) {
		$errors .= '<h4 class="error"><b>error:</b> The username is already registered.</h4>';
	}
	if (empty($password) || $password == " ") {
		$errors .= '<h4 class="error"><b>error:</b> Please enter your password.</h4>';
	}
	if (empty($r_password) || $r_password == " ") {
		$errors .= '<h4 class="error"><b>error:</b> Please re-enter your password.</h4>';
	}
	if (!compare_passwords($password, $r_password)) {
		$errors .= '<h4 class="error"><b>error:</b> Please re-enter your password correctly.</h4>';
	}
	return $errors;
}

function username_exists($username)
{
	global $connection;
	$sql = 'SELECT username FROM users WHERE username = "' . $username . '"';
	$check_username = mysqli_fetch_array(mysqli_query($connection, $sql));
	if ($check_username == "") {
		return false;
	} else {
		return true;
	}
}

function compare_passwords($username, $r_password)
{
	if ($username == $r_password) {
		return true;
	} else {
		return false;
	}
}
//  ---------------------------------------
