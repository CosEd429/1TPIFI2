<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['pk_username'])) {
    header("Location: Login.php");
    exit();
}

$username = $_SESSION['pk_username'];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        // Update name/email
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        
        // Update database
        $sql = "UPDATE user SET firstName = ?, lastName = ?, email = ? WHERE pk_username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $email, $username);
        
        if (mysqli_stmt_execute($stmt)) {
            // Update session
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['email'] = $email;
            
            $_SESSION['success'] = "Profile updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update profile.";
        }
    }
    
    if (isset($_POST['change_password'])) {
        // Change password logic
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Verify current password
        // Update to new password
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>My Profile</title>
</head>
<body>
<?php include("header.php"); ?>

<!-- Use same dashboard navigation -->
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
        <form method="POST" action="Profile.php">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" value="<?php echo htmlspecialchars($_SESSION['pk_username']); ?>" disabled>
                <small>Username cannot be changed</small>
            </div>
            
            <div class="form-group">
                <label for="firstName">First Name *</label>
                <input type="text" id="firstName" name="firstName" 
                       value="<?php echo htmlspecialchars($_SESSION['firstName']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="lastName">Last Name *</label>
                <input type="text" id="lastName" name="lastName" 
                       value="<?php echo htmlspecialchars($_SESSION['lastName']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
            </div>
            
            <button type="submit" name="update_profile" class="btn-primary">
                Update Profile
            </button>
        </form>
    </div>
    
    <!-- Change Password Form -->
    <div class="profile-section">
        <h3>Change Password</h3>
        <form method="POST" action="Profile.php">
            <div class="form-group">
                <label for="current_password">Current Password *</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password *</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" name="change_password" class="btn-primary">
                Change Password
            </button>
        </form>
    </div>
    
    <!-- Account Actions -->
    <div class="profile-section">
        <h3>Account Actions</h3>
        <p>
            <a href="Logout.php" class="btn-secondary">Logout</a>
            <!-- Optional: Account deletion -->
            <!-- <button class="btn-danger">Delete Account</button> -->
        </p>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>