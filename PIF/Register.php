<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
<form method = "POST" action = "AuthenticationPage.php">
    <h1>Create a new user</h1>
    <p>Username: <input type = "text" name = "newUsername" required> </p>
    <p>Password: <input type = "password" name = "newPassword" required> </p>
    <p>Confirm your password: <input type = "password" name = "confirm_password" required> </p>
    <input type = "submit" name = "Sign-up" value = "Sign-up">
</form>
<?php if(isset($_SESSION["error"])): ?>
        <div class="error"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></div>
    <?php endif; ?>
</body>
</html>