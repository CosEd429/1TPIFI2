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
<?php 
// Check if user is already logged in
if(isset($_SESSION['pk_username'])) {  // CHANGED: 'username' to 'pk_username'
    header("Location: WelcomePage.php");
    exit();
}

include("header.php"); 
?>

<!-- SIMPLE NAVIGATION -->
<div class="simple-nav">
    <a href="WelcomePage.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
</div>

<div class="content">
    <?php if(isset($_SESSION["authenticate"])): ?>
        <div class="error-message">
            <?php 
            echo $_SESSION["authenticate"];
            unset($_SESSION["authenticate"]);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION["success"])): ?>
        <div class="success-message">
            <?php 
            echo $_SESSION["success"];
            unset($_SESSION["success"]);
            ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="AuthenticationPage.php">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <input type="submit" name="Login" value="Login">
        </div>
        
        <p>Don't have an account? <a href="Register.php">Register here</a></p>
    </form>
</div>

<?php include("footer.php"); ?>
</body>
</html>