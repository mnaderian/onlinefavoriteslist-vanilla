<?php

require('includes/functions.php');
$_SESSION['page'] = 'index.php';
check_user_login();

if (isset($_POST['url'])) {
    add_site($_POST['url'], $_POST['addsite_cat']);
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    delete_fav($id);
}

if (isset($_GET['clear_list']) && $_GET['clear_list'] == 1) {
    clear_list($_SESSION['username']);
}

if (isset($_POST['list_cats'])) {
    $_SESSION['selected_cat'] = $_POST['list_cats'];
    if ($_POST['list_cats'] == 'all') {
        unset($_SESSION['selected_cat']);
    }
    redirect('index.php');
}

?>
<html>

<head>
    <title><?php echo $index_title; ?> <?php echo $app_version; ?></title>
    <link href="assets/styles/default.css" type="text/css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="assets/images/favorites.png" />
</head>

<body>

    <div id="main">
        <div id="icon"></div>
        <h1><?php echo $index_title; ?> <span><?php echo $app_version; ?></span></h1>
        <div id="welcome_logout"><b>Welcome </b><?php echo $_SESSION['username']; ?> |
            <span><a href="logout.php">Log out</a></span></div>
        <fieldset id="addsite">
            <legend>Add Site</legend>
            <div id="addform">
                <form method="post" action="index.php">
                    <label>URL: </label>
                    <input type="text" name="url" class="text" />
                    <select name="addsite_cat" id="addsite_cat">
                        <?php print_options_user_categories($_SESSION['username']); ?>
                    </select>
                    <input type="submit" name="add" value="Add" class="submit" />
                </form>
            </div>
        </fieldset>
        <div id="list_options">
            <b>Options: </b>
            <a href="index.php?clear_list=1" onclick="return confirm('Are you sure you want to clear this list?');">
                Clear List</a>
            <a href="profile.php">My Profile</a>
            <a href="settings.php">Settings</a>
            <a href="categories.php">Categories</a>
            <a href="trash.php">Trash</a>
        </div>

        <div id="number_of_favorites">
            <i>Number of favorites: </i>
            <?php echo number_of_favorites($_SESSION['username']); ?>
        </div>
        <form id="categories" name="categories" method="post" action="index.php">
            <i>Category: </i>&nbsp;
            <select name="list_cats">
                <option value="all">All</option>
                <?php
                    print_options_user_categories_selected($_SESSION['username']);
                ?>
            </select>
            <input type="submit" value="Load" />
        </form>
        <fieldset id="favlist">
            <legend>Favorites List</legend>
            <div id="fav_list">
                <?php
                $user_id = get_user_id($_SESSION['username']);
                $favorites_list = get_favorites_list($user_id);
                print_favorites_list($favorites_list, $_SESSION['username']);
                ?>
            </div>
        </fieldset>
    </div>
</body>

</html>
