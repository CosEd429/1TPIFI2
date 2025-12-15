<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
    <link href='https://fonts.googleapis.com/css?family=Baskervville SC' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
<?php include("header.php"); ?>

<div class="menu">
    <h1>Login</h1>
    <a href="HomePage.php">HomePage</a>
    <a href="Login-form.php">Login</a>
    <a href="Sign-up.php">SignUp</a>
    <a href="ProductsPage.php">Products</a>
</div>

<div class="content">
    <?php if(isset($_SESSION["authenticate"])): ?>
        <div class="error-message">
            <?php 
            echo $_SESSION["authenticate"];
            unset($_SESSION["authenticate"]); // Clear the error after displaying
            ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="AuthenticationPage.php">
        <p>Username: <input type="text" name="username" required></p>
        <p>Password: <input type="password" name="password" required></p>
        <input type="submit" name="Login" value="Login">
    </form>
</div>

<?php include("footer.php"); ?>
</body>
</html>