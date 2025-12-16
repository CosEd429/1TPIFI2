<?php
session_start();

// Check if user is already logged in
if(isset($_SESSION['pk_username'])) {
    header("Location: WelcomePage.php");
    exit();
}
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
    <title>Login - Portable Indoor Feedback</title>
</head>
<body class="auth-page"> <!-- ADD THIS -->
    
    <div class="auth-container">
        <!-- Back to Home Link -->
        <a href="WelcomePage.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
        
        <!-- Logo/Title -->
        <div class="auth-header">
            <h1>Login</h1>
            <p>Welcome back to Portable Indoor Feedback</p>
        </div>
        
        <!-- Error/Success Messages -->
        <?php if(isset($_SESSION["authenticate"])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION["authenticate"];
                unset($_SESSION["authenticate"]);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION["success"])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION["success"];
                unset($_SESSION["success"]);
                ?>
            </div>
        <?php endif; ?>
        
        <!-- Login Form -->
        <form method="POST" action="AuthenticationPage.php" class="auth-form">
            <div class="form-group">
                <label for="username">Username </label>
                <input type="text" id="username" name="username" required 
                       placeholder="Enter your username">
            </div>
            
            <div class="form-group">
                <label for="password">Password </label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter your password">
            </div>
            
            <div class="form-group">
                <button type="submit" name="Login" class="btn-submit">
                    Login
                </button>
            </div>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="Register.php">Register here</a></p>
            </div>
        </form>
    </div>
    
</body>
</html>