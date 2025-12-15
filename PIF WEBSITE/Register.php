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
    <title>Register</title>
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

<!-- SIMPLE NAVIGATION (or remove this if you want minimal navigation) -->
<div class="simple-nav">
    <a href="WelcomePage.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
</div>

<div class="content">
    <?php if(isset($_SESSION["error"])): ?>
        <div class="error-message">
            <?php 
            echo $_SESSION["error"];
            unset($_SESSION["error"]);
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
        <h2>Create a new User</h2>
        
        <!-- USERNAME FIELD -->
        <div class="form-group">
            <label for="newusername">Username:</label>
            <input type="text" id="newusername" name="newusername" required>
        </div>
        
        <!-- FIRST NAME FIELD -->
        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>
        </div>
        
        <!-- LAST NAME FIELD -->
        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>
        </div>
        
        <!-- EMAIL FIELD -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <!-- PASSWORD FIELD -->
        <div class="form-group">
            <label for="newpassword">Password:</label>
            <input type="password" id="newpassword" name="newpassword" required>
        </div>
        
        <!-- CONFIRM PASSWORD FIELD -->
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <!-- SUBMIT BUTTON -->
        <div class="form-group">
            <input type="submit" name="Sign-up" value="Register">
        </div>
        
        <p>Already have an account? <a href="Login.php">Login here</a></p>
    </form>    
</div>

<?php include("footer.php"); ?>
</body>
</html>