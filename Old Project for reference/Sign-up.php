<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
    <link href='https://fonts.googleapis.com/css?family=Baskervville SC' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include("header.php"); ?>

<div class="menu">
    <h1>Sign Up</h1>
    <a href="HomePage.php">HomePage</a>
    <a href="Login-form.php">Login</a>
    <a href="Sign-up.php">SignUp</a>
    <a href="ProductsPage.php">Products</a>
</div>

<div class="content">
    <?php if(isset($_SESSION["error"])): ?>
        <div class="error"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="AuthenticationPage.php">
        <h1>Create a new User</h1>
        <p>Username: <input type="text" name="newusername" required></p>
        <p>Password: <input type="password" name="newpassword" required></p>
        <p>Confirm Password: <input type="password" name="confirm_password" required></p>
        <input type="submit" name="Sign-up" value="Sign-up">
    </form>    
</div>

<?php include("footer.php"); ?>
</body>
</html>