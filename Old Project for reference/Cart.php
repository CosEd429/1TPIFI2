<?php
session_start();
require_once 'database.php';

// Check if user is logged in
if (!isset($_SESSION['pk_userID'])) {
    header("Location: Login-form.php");
    exit();
}

// Handle removing items from cart
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['success'] = "Item removed from cart";
    }
    header("Location: Cart.php");
    exit();
}

// Handle placing order
if (isset($_POST['place_order'])) {
    if (!empty($_SESSION['cart'])) {
        mysqli_begin_transaction($conn);
        
        try {
            // 1. Calculate total
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // 2. Create order record
            $order_sql = "INSERT INTO orders (fk_userID, total) VALUES (?, ?)";
            $order_stmt = mysqli_prepare($conn, $order_sql);
            mysqli_stmt_bind_param($order_stmt, "id", $_SESSION['pk_userID'], $total);
            
            if (!mysqli_stmt_execute($order_stmt)) {
                throw new Exception("Failed to create order record: " . mysqli_error($conn));
            }
            
            $order_id = mysqli_insert_id($conn);
            mysqli_stmt_close($order_stmt);

            // 3. Add order items
            $item_sql = "INSERT INTO order_items (fk_orderID, fk_productID, quantity, price) VALUES (?, ?, ?, ?)";
            $item_stmt = mysqli_prepare($conn, $item_sql);
            
            foreach ($_SESSION['cart'] as $product_id => $item) {
                // Verify product exists
                $check_sql = "SELECT pk_itemID FROM products WHERE pk_itemID = ?";
                $check_stmt = mysqli_prepare($conn, $check_sql);
                mysqli_stmt_bind_param($check_stmt, "i", $product_id);
                mysqli_stmt_execute($check_stmt);
                mysqli_stmt_store_result($check_stmt);
                
                if (mysqli_stmt_num_rows($check_stmt) == 0) {
                    throw new Exception("Product ID $product_id not found");
                }
                mysqli_stmt_close($check_stmt);

                // Add order item
                mysqli_stmt_bind_param($item_stmt, "iiid", $order_id, $product_id, $item['quantity'], $item['price']);
                
                if (!mysqli_stmt_execute($item_stmt)) {
                    throw new Exception("Failed to add order item: " . mysqli_error($conn));
                }

                // 4. Update stock (optional)
                $update_sql = "UPDATE products SET stock = stock - ? WHERE pk_itemID = ?";
                $update_stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($update_stmt, "ii", $item['quantity'], $product_id);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
            }
            
            // Commit transaction if all succeeded
            mysqli_commit($conn);
            
            // Clear cart and redirect
            unset($_SESSION['cart']);
            $_SESSION['order_success'] = "Order #$order_id placed successfully!";
            header("Location: OrderConfirmation.php");
            exit();
            
        } catch (Exception $e) {
            // Rollback on any error
            mysqli_rollback($conn);
            $_SESSION['error'] = "Order failed: " . $e->getMessage();
            error_log("Order Error: " . $e->getMessage());
            header("Location: Cart.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Your cart is empty";
        header("Location: Cart.php");
        exit();
    }
}

// Fetch current cart products from database
$cart_products = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));
    
    $sql = "SELECT pk_itemID, name, price FROM products WHERE pk_itemID IN ($placeholders)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$product_ids);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_products[$row['pk_itemID']] = $row;
        $total += $row['price'] * $_SESSION['cart'][$row['pk_itemID']]['quantity'];
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
    <link href='https://fonts.googleapis.com/css?family=Baskervville SC' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include("header.php"); ?>

<div class="menu">
    <h1>Your Cart</h1>
    <?php
    echo "<a href='HomePage.php'>HomePage</a>";
    if (isset($_SESSION["username"])) {
        echo "<a href='Logout.php'>Logout</a>";
        echo "<a href='ProductsPage.php'>Products</a>";
        if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
            echo "<a href='ConfigurationPage.php'>Configuration</a>";
        }
    } else {
        echo "<a href='Login-form.php'>Login</a>";
        echo "<a href='Sign-up.php'>Sign Up</a>";
    }
    ?>
</div>

<div class="content">
    <h2>Your Shopping Cart</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
        <a href="ProductsPage.php" class="button">Continue Shopping</a>
    <?php else: ?>
        <table class="cart-table">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                <?php if (isset($cart_products[$product_id])): ?>
                    <tr>
                        <td><?= htmlspecialchars($cart_products[$product_id]['name']) ?></td>
                        <td>$<?= number_format($cart_products[$product_id]['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($cart_products[$product_id]['price'] * $item['quantity'], 2) ?></td>
                        <td><a href="Cart.php?remove=<?= $product_id ?>">Remove</a></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>$<?= number_format($total, 2) ?></td>
                <td></td>
            </tr>
        </table>
        
        <form method="post" action="Cart.php">
            <input type="submit" name="place_order" value="Place Order" class="checkout-button">
        </form>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>
</body>
</html>
<?php
mysqli_close($conn);
?>