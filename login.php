<?php

	include('includes/functions.php');

?>
<html>

<head>
    <title>Login</title>
    <link href="assets/styles/login.css" type="text/css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="assets/images/favorites.png" />
</head>

<body>
    <div id="main">
        <h3>Favorites List <span><?php echo $app_version; ?></span></h3>
        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        <fieldset>
            <legend>Login</legend>
            <form id="login_form" method="post" action="login_check.php">
                <label>Username: </label><input class="text" type="text" name="username" /><br />
                <label>Password: </label><input class="text" type="password" name="password" /><br />
                <input class="submit" type="submit" value="Login" name="login" />
            </form>
        </fieldset>
        <a id="register_here" href="register.php" title="Register here, please!">Register here</a>
    </div>
</body>

</html>