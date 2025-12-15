<?php
require_once 'database.php';

// Function to display migration status
function log_migration($message) {
    echo "[*] " . $message . "<br>";
    flush();
}

// 1. MIGRATE USERS
log_migration("Starting users migration...");
$users = file('users.txt');
$user_map = []; // To store mapping of usernames to their new IDs

foreach ($users as $user_line) {
    if (empty(trim($user_line))) continue;
    
    $data = explode(',', trim($user_line));
    if (count($data) >= 3) {
        $username = trim($data[0]);
        $password = trim($data[1]);
        $role = trim($data[2]);
        
        // Check if user already exists
        $check_sql = "SELECT pk_userID FROM users WHERE username = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            log_migration("User {$username} already exists - skipping");
            mysqli_stmt_close($check_stmt);
            continue;
        }
        mysqli_stmt_close($check_stmt);
        
        // Insert new user
        $insert_sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "sss", $username, $password, $role);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $user_id = mysqli_insert_id($conn);
            $user_map[$username] = $user_id;
            log_migration("Migrated user: {$username} (ID: {$user_id})");
        } else {
            log_migration("Error migrating user {$username}: " . mysqli_error($conn));
        }
        mysqli_stmt_close($insert_stmt);
    }
}

// 2. MIGRATE PRODUCTS
log_migration("\nStarting products migration...");
$products = file('products.txt');
$product_map = []; // To store mapping of product names to their new IDs

foreach ($products as $product_line) {
    if (empty(trim($product_line))) continue;
    
    $data = explode(',', trim($product_line));
    if (count($data) >= 3) {
        $name = trim($data[0]);
        $price = floatval(trim($data[1]));
        $cover_image = trim($data[2]);
        
        // Check if product already exists
        $check_sql = "SELECT pk_itemID FROM products WHERE name = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $name);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            log_migration("Product {$name} already exists - skipping");
            mysqli_stmt_close($check_stmt);
            continue;
        }
        mysqli_stmt_close($check_stmt);
        
        // Insert new product
        $insert_sql = "INSERT INTO products (name, price, cover_image) VALUES (?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "sds", $name, $price, $cover_image);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $product_id = mysqli_insert_id($conn);
            $product_map[$name] = $product_id;
            log_migration("Migrated product: {$name} (ID: {$product_id})");
        } else {
            log_migration("Error migrating product {$name}: " . mysqli_error($conn));
        }
        mysqli_stmt_close($insert_stmt);
    }
}

// 3. MIGRATE ORDERS (MOST COMPLEX PART)
log_migration("\nStarting orders migration...");
$orders = file('orders.txt');

foreach ($orders as $order_line) {
    if (empty(trim($order_line))) continue;
    
    // Parse order line: username,date,items,total
    $data = explode(',', trim($order_line));
    if (count($data) >= 4) {
        $username = trim($data[0]);
        $order_date = trim($data[1]);
        $items_str = trim($data[2]);
        $total = floatval(trim($data[3]));
        
        // Get user ID from our map
        if (!isset($user_map[$username])) {
            log_migration("User {$username} not found - skipping order");
            continue;
        }
        $user_id = $user_map[$username];
        
        // Start transaction for order + order items
        mysqli_begin_transaction($conn);
        
        try {
            // Insert order
            $order_sql = "INSERT INTO orders (fk_userID, total, order_date) VALUES (?, ?, ?)";
            $order_stmt = mysqli_prepare($conn, $order_sql);
            mysqli_stmt_bind_param($order_stmt, "ids", $user_id, $total, $order_date);
            
            if (!mysqli_stmt_execute($order_stmt)) {
                throw new Exception("Failed to insert order: " . mysqli_error($conn));
            }
            $order_id = mysqli_insert_id($conn);
            mysqli_stmt_close($order_stmt);
            
            log_migration("Created order #{$order_id} for {$username}");
            
            // Process order items (format: name|quantity|price;name|quantity|price)
            $items = explode(';', $items_str);
            foreach ($items as $item) {
                $item_data = explode('|', $item);
                if (count($item_data) >= 3) {
                    $product_name = trim($item_data[0]);
                    $quantity = intval(trim($item_data[1]));
                    $price = floatval(trim($item_data[2]));
                    
                    // Get product ID from our map
                    if (!isset($product_map[$product_name])) {
                        throw new Exception("Product {$product_name} not found");
                    }
                    $product_id = $product_map[$product_name];
                    
                    // Insert order item
                    $item_sql = "INSERT INTO order_items (fk_orderID, fk_productID, quantity, price) VALUES (?, ?, ?, ?)";
                    $item_stmt = mysqli_prepare($conn, $item_sql);
                    mysqli_stmt_bind_param($item_stmt, "iiid", $order_id, $product_id, $quantity, $price);
                    
                    if (!mysqli_stmt_execute($item_stmt)) {
                        throw new Exception("Failed to insert order item: " . mysqli_error($conn));
                    }
                    mysqli_stmt_close($item_stmt);
                    
                    log_migration("  - Added item: {$product_name} (Qty: {$quantity})");
                }
            }
            
            // Commit transaction if all went well
            mysqli_commit($conn);
            log_migration("Successfully migrated order #{$order_id}");
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            log_migration("ERROR: " . $e->getMessage());
        }
    }
}

log_migration("\nMigration complete!");
mysqli_close($conn);
?>