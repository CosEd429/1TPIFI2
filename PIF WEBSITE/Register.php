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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register - Portable Indoor Feedback</title>
</head>
<body class="auth-page">
    
    <div class="auth-container">
        <!-- Back to Home Link -->
        <a href="WelcomePage.php" class="back-link">
            ← Back to Home
        </a>
        
        <!-- Logo/Title -->
        <div class="auth-header">
            <h1>Create Account</h1>
            <p>Join Portable Indoor Feedback to start monitoring your indoor climate</p>
        </div>
        
        <!-- Error/Success Messages -->
        <?php if(isset($_SESSION["error"])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION["error"];
                unset($_SESSION["error"]);
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
        
        <!-- Registration Form -->
        <!-- In Register.php, ensure form structure is: -->
<form method="POST" action="AuthenticationPage.php" class="auth-form">
    
    <!-- Username - on its own line -->
    <div class="form-group">
        <label for="newusername">Username </label>
        <input type="text" id="newusername" name="newusername" required>
    </div>
    
    <!-- First Name - on its own line -->
    <div class="form-group">
        <label for="firstName">First Name </label>
        <input type="text" id="firstName" name="firstName" required>
    </div>
    
    <!-- Last Name - on its own line -->
    <div class="form-group">
        <label for="lastName">Last Name </label>
        <input type="text" id="lastName" name="lastName" required>
    </div>
    
    <!-- Email - on its own line -->
    <div class="form-group">
        <label for="email">Email </label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <!-- Password - on its own line -->
    <div class="form-group">
        <label for="newpassword">Password </label>
        <input type="password" id="newpassword" name="newpassword" required>
    </div>
    
    <!-- Confirm Password - on its own line -->
    <div class="form-group">
        <label for="confirm_password">Confirm Password </label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    
    <!-- Submit Button - on its own line -->
    <div class="form-group">
        <button type="submit" name="Sign-up" class="btn-submit">
            Create Account
        </button>
    </div>
            <div class="auth-footer">
                <p>Already have an account? <a href="Login.php">Sign in here</a></p>
            </div>
        </form>
    </div>
    
</body>
</html>