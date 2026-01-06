<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['pk_username'])) {
    header("Location: Login.php");
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
    <title>My Profile</title>
</head>
<body>
<?php include("header.php"); ?>

<div class="menu">
    <h1>My Profile</h1>
    <a href="WelcomePage.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="Stations.php"><i class="fas fa-thermometer-half"></i> My Stations</a>
    <a href="Measurements.php"><i class="fas fa-chart-line"></i> Measurements</a>
    <a href="Collections.php"><i class="fas fa-folder"></i> Collections</a>
    <a href="Friends.php"><i class="fas fa-users"></i> Friends</a>
    <a href="Profile.php" class="active"><i class="fas fa-user"></i> Profile</a>
    <?php if ($_SESSION['admin']): ?>
        <a href="AdminConfiguration.php"><i class="fas fa-cog"></i> Admin</a>
    <?php endif; ?>
    <a href="Logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">
    <h2>Profile Settings</h2>
    
    <!-- Success/Error Messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <!-- Personal Information Form -->
    <div class="profile-section">
        <h3>Personal Information</h3>
        <form method="POST" action="AuthenticationPage.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo htmlspecialchars($_SESSION['pk_username']); ?>">
            </div>
            
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" 
                       value="<?php echo htmlspecialchars($_SESSION['firstName']); ?>">
            </div>
            
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" 
                       value="<?php echo htmlspecialchars($_SESSION['lastName']); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
            </div>
            
            <div class="form-group">
                <input type="submit" name="update_profile" value="Update Profile" class="btn-primary">
            </div>
        </form>
    </div>
    
    <!-- Change Password Form -->
    <div class="profile-section">
        <h3>Change Password</h3>
        <form method="POST" action="AuthenticationPage.php">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password">
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            
            <div class="form-group">
                <input type="submit" name="change_password" value="Change Password" class="btn-primary">
            </div>
        </form>
    </div>
    
    <!-- Account Actions -->
    <div class="profile-section">
        <h3>Account Actions</h3>
        <div class="form-group">
            <a href="Logout.php" class="btn-secondary">Logout</a>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>