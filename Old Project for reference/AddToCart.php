<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION["username"])) {
    $_SESSION["login_redirect"] = "ProductsPage.php";
    header("Location: Login-form.php");
    exit;
}

// Check if form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    if(empty($_POST["product_name"]) || empty($_POST["product_price"])) {
        $_SESSION["error"] = "Product information missing";
        header("Location: ProductsPage.php");
        exit;
    }

    $product_name = $_POST["product_name"];
    $product_price = $_POST["product_price"];
    $quantity = isset($_POST["quantity"]) ? max(1, (int)$_POST["quantity"]) : 1;
    
    // Initialize cart if it doesn't exist
    if(!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }
    
    // Check if product already exists in cart
    $product_exists = false;
    foreach($_SESSION["cart"] as &$item) {
        if($item["product_name"] == $product_name) {
            $item["quantity"] += $quantity;
            $product_exists = true;
            break;
        }
    }
    
    // If product doesn't exist in cart, add it
    if(!$product_exists) {
        $_SESSION["cart"][] = [
            "product_name" => $product_name,
            "product_price" => $product_price,
            "quantity" => $quantity
        ];
    }
    
    // Set success message
    $_SESSION["cart_success"] = "$product_name added to cart!";
}

// Always redirect back to ProductsPage.php
header("Location: ProductsPage.php");
exit;
?>