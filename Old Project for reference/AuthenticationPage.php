<?php
session_start();
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                
                if ($user['password'] === $password) {
                    $_SESSION['pk_userID'] = $user['pk_userID'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['admin'] = ($user['role'] === 'Admin'); // This is the key line
                    
                    header("Location: HomePage.php");
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
        
        // Redirect back to login page on failure
        header("Location: Login-form.php");
        exit();
    }
    
    // SIGNUP PROCESSING
    if (isset($_POST['Sign-up'])) {
        $newusername = $_POST['newusername'];
        $newpassword = $_POST['newpassword'];
        $confirm_password = $_POST['confirm_password'];
        
        // Check if passwords match
        if ($newpassword !== $confirm_password) {
            $_SESSION['error'] = "Passwords don't match";
            header("Location: Sign-up.php");
            exit();
        }
        
        // Check if username exists
        $sql = "SELECT pk_userID FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $newusername);
        mysqli_stmt_execute($stmt);
        
        // Store result to check row count
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $_SESSION['error'] = "Username already taken";
            mysqli_stmt_close($stmt);
            header("Location: Sign-up.php");
            exit();
        }
        mysqli_stmt_close($stmt);
        
        // Insert new user
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $newusername, $newpassword);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: Login-form.php");
        } else {
            $_SESSION['error'] = "Registration failed";
            header("Location: Sign-up.php");
        }
        mysqli_stmt_close($stmt);
    }
}
// Close database connection
mysqli_close($conn);
?>