<?php
session_start();
require_once 'database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
    <link href='https://fonts.googleapis.com/css?family=Baskervville SC' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include("header.php"); ?>

<div class="menu">
    <h1>Products</h1>
    <?php
    echo "<a href='HomePage.php'>HomePage</a>";
    if(isset($_SESSION["username"])) {
        echo "<a href='Logout.php'>Logout</a>";
        echo "<a href='Cart.php'>View Cart</a>";
        if(isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
            echo "<a href='ConfigurationPage.php'>Configuration</a>";
        }
    } else {
        echo "<a href='Login-form.php'>Login</a>";
        echo "<a href='Sign-up.php'>Sign Up</a>";
    }
    echo "<a href='ProductsPage.php'>Products</a>";
    ?>
</div>

<div class="content">
    <?php if(isset($_SESSION["cart_success"])): ?>
        <div class="success-message"><?php 
            echo htmlspecialchars($_SESSION["cart_success"]); 
            unset($_SESSION["cart_success"]);
        ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION["error"])): ?>
        <div class="error-message"><?php 
            echo htmlspecialchars($_SESSION["error"]); 
            unset($_SESSION["error"]);
        ?></div>
    <?php endif; ?>
    
    <h2>Our Products</h2>
    <div class="product-grid">
        <?php
        try {
            $sql = "SELECT * FROM products";
            $result = mysqli_query($conn, $sql);
            
            if ($result && mysqli_num_rows($result) > 0) {
                while ($product = mysqli_fetch_assoc($result)) {
                    echo "<div class='product'>";
                    echo "<img src='uploads/" . htmlspecialchars($product['cover_image']) . "' 
                          alt='" . htmlspecialchars($product['name']) . "'
                          onerror=\"this.src='images/default.jpg'\">";
                    echo "<h3>" . htmlspecialchars($product['name']) . "</h3>";
                    echo "<p>Price: $" . number_format($product['price'], 2) . "</p>";
                    
                    if(isset($_SESSION["username"])) {
                        echo "<form method='post' action='AddToCart.php'>";
                        echo "<input type='hidden' name='product_id' value='" . $product['pk_itemID'] . "'>";
                        echo "<input type='hidden' name='product_name' value='" . htmlspecialchars($product['name']) . "'>";
                        echo "<input type='hidden' name='product_price' value='" . $product['price'] . "'>";
                        echo "<input type='number' name='quantity' value='1' min='1' style='width: 50px;'>";
                        echo "<input type='submit' value='Add to Cart'>";
                        echo "</form>";
                    }
                    
                    echo "</div>";
                }
            } else {
                echo "<p>No products available.</p>";
            }
        } catch (Exception $e) {
            echo "<div class='error-message'>Error loading products: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
        ?>
    </div>
</div>

<?php include("footer.php"); 
mysqli_close($conn);
?>
</body>
</html>