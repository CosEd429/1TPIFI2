<?php

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['Login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
    }
}

if (isset($_POST['Sign-up'])) {
        $newusername = $_POST['newusername'];
        $newpassword = $_POST['newpassword'];
        $confirm_password = $_POST['confirm_password'];
        
        // Check if passwords match
        if ($newpassword !== $confirm_password) {
            $_SESSION['error'] = "Passwords don't match";
            header("Location: Register.php");
            exit();
        }
    }