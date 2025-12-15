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
        // For production: hash the password
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $plain_password = $password; // For testing
        
        // Insert new user
        $sql = "INSERT INTO user (pk_username, firstName, lastName, password, email, role) 
                VALUES (?, ?, ?, ?, ?, 'User')";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", 
            $username, 
            $firstName, 
            $lastName, 
            $plain_password, // For testing
            // $hashed_password, // For production
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
}

// Don't close connection if other files need it
?>