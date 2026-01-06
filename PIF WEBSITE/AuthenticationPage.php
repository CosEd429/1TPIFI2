<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // LOGIN PROCESSING
    if (isset($_POST['Login'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        $sql = "SELECT pk_username, firstName, lastName, email, password, role FROM user WHERE pk_username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                
                // For testing with plain text passwords, use:
                if ($password === $user['password']) {
                // For production with hashed passwords, use:
                // if (password_verify($password, $user['password'])) {
                    
                    $_SESSION['pk_username'] = $user['pk_username'];
                    $_SESSION['firstName'] = $user['firstName'];
                    $_SESSION['lastName'] = $user['lastName'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['admin'] = ($user['role'] === 'Admin'); // This sets the admin flag
                    
                    header("Location: WelcomePage.php");
                    exit();
                } else {
                    $_SESSION["authenticate"] = "Invalid password";
                }
            } else {
                $_SESSION["authenticate"] = "User not found";
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION["authenticate"] = "Database error";
        }
        
        header("Location: Login.php");
        exit();
    }
    
        // REGISTRATION PROCESSING
    if (isset($_POST['Sign-up'])) {
        $username = trim($_POST['newusername']);
        $password = $_POST['newpassword'];
        $confirm_password = $_POST['confirm_password'];
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        
        // Check if passwords match
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Passwords don't match";
            header("Location: Register.php");
            exit();
        }
        
        // Check if username or email already exists
        $sql = "SELECT pk_username FROM user WHERE pk_username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $_SESSION['error'] = "Username or email already taken";
            mysqli_stmt_close($stmt);
            header("Location: Register.php");
            exit();
        }
        mysqli_stmt_close($stmt);
        
        // For testing: store plain text password
        $plain_password = $password;
        
        // Insert new user 
        $sql = "INSERT INTO user (pk_username, firstName, lastName, password, email, role) 
                VALUES (?, ?, ?, ?, ?, 'User')";
        $stmt = mysqli_prepare($conn, $sql);
        
        // 5 string parameters: username, firstName, lastName, password, email
        mysqli_stmt_bind_param($stmt, "sssss", 
            $username, 
            $firstName, 
            $lastName, 
            $plain_password,
            $email
        );
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: Login.php");
        } else {
            $_SESSION['error'] = "Registration failed: " . mysqli_error($conn);
            header("Location: Register.php");
        }
        mysqli_stmt_close($stmt);
    }
    // ========== PROFILE UPDATE PROCESSING ==========
    if (isset($_POST['update_profile'])) {
        $currentUsername = $_SESSION['pk_username'];
        $newUsername = trim($_POST['username']);
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        
        // Check if username is being changed
        if ($newUsername !== $currentUsername) {
            // Check if new username already exists
            $check_sql = "SELECT pk_username FROM user WHERE pk_username = ?";
            $check_stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "s", $newUsername);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                $_SESSION['error'] = "Username already taken. Please choose another.";
                mysqli_stmt_close($check_stmt);
                header("Location: Profile.php");
                exit();
            }
            mysqli_stmt_close($check_stmt);
        }
        
        // Update user information
        $sql = "UPDATE user SET pk_username = ?, firstName = ?, lastName = ?, email = ? 
                WHERE pk_username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $newUsername, $firstName, $lastName, $email, $currentUsername);
        
        if (mysqli_stmt_execute($stmt)) {
            // Update session variables
            $_SESSION['pk_username'] = $newUsername;
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['email'] = $email;
            
            $_SESSION['success'] = "Profile updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update profile.";
        }
        
        header("Location: Profile.php");
        exit();
    }
}

    // ========== STATION REGISTRATION PROCESSING ==========
    if (isset($_POST['register_station'])) {
        $username = $_SESSION['pk_username'];
        $serial = trim($_POST['serial_number']);
        
        // Validate serial number format (optional)
        if (empty($serial)) {
            $_SESSION['error'] = "Please enter a serial number";
            header("Location: Stations.php");
            exit();
        }
        
        // Check if station exists and is unassigned
        $sql = "SELECT * FROM station WHERE pk_serialNumber = ? AND fk_user_owns IS NULL";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $serial);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            // Assign to user
            $update_sql = "UPDATE station SET fk_user_owns = ? WHERE pk_serialNumber = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "ss", $username, $serial);
            
            if (mysqli_stmt_execute($update_stmt)) {
                $_SESSION['success'] = "Station registered successfully!";
            } else {
                $_SESSION['error'] = "Failed to register station.";
            }
        } else {
            $_SESSION['error'] = "Station not found or already registered.";
        }
        
        mysqli_stmt_close($stmt);
        header("Location: Stations.php");
        exit();
    }
    
    // ========== STATION UPDATE PROCESSING ==========
    if (isset($_POST['update_station'])) {
        $username = $_SESSION['pk_username'];
        $serial = $_POST['station_serial'];
        $name = trim($_POST['station_name']);
        $description = trim($_POST['station_description']);
        
        // Verify user owns this station (unless admin)
        $check_sql = "SELECT pk_serialNumber FROM station WHERE pk_serialNumber = ? AND fk_user_owns = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "ss", $serial, $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0 || $_SESSION['admin']) {
            // User owns station or is admin - update it
            $sql = "UPDATE station SET name = ?, description = ? WHERE pk_serialNumber = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $name, $description, $serial);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Station updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update station.";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "You don't have permission to edit this station.";
        }
        
        mysqli_stmt_close($check_stmt);
        header("Location: Stations.php");
        exit();
    }
// Don't close connection if other files need it
?>