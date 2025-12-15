<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("Location: Login-form.php");
    exit();
}

$upload_dir = "uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    $sql = "DELETE FROM products WHERE pk_itemID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Product deleted successfully";
    } else {
        $_SESSION['error'] = "Error deleting product";
    }
    mysqli_stmt_close($stmt);
    header("Location: ConfigurationPage.php");
    exit();
}

// Handle product add/edit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['bookName']);
    $price = floatval($_POST['price']);
    $type = isset($_POST['type']) ? mysqli_real_escape_string($conn, $_POST['type']) : '';
    $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : null;
    
    // Handle file upload
    $cover_image = '';
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == UPLOAD_ERR_OK) {
        $file_ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed)) {
            $new_filename = uniqid('', true) . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $destination)) {
                $cover_image = $new_filename;
            }
        }
    } elseif (isset($_POST['existing_cover'])) {
        $cover_image = mysqli_real_escape_string($conn, $_POST['existing_cover']);
    }
    
    if ($edit_id) {
        $sql = "UPDATE products SET name=?, type=?, price=?, stock=?, cover_image=? WHERE pk_itemID=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdisi", $name, $type, $price, $stock, $cover_image, $edit_id);
    } else {
        $sql = "INSERT INTO products (name, type, price, stock, cover_image) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdis", $name, $type, $price, $stock, $cover_image);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Product " . ($edit_id ? "updated" : "added") . " successfully";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
    header("Location: ConfigurationPage.php");
    exit();
}

// Fetch all products
$products = [];
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Configuration</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
    <link href='https://fonts.googleapis.com/css?family=Baskervville SC' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include("header.php"); ?>

<div class="menu">
    <h1>Configuration</h1>
    <?php
    echo "<a href='HomePage.php'>HomePage</a>";
    if(isset($_SESSION["username"])) {
        echo "<a href='Logout.php'>Logout</a>";
    } else {
        echo "<a href='Login-form.php'>Login</a>";
        echo "<a href='Sign-up.php'>Sign Up</a>";
    }
    echo "<a href='ProductsPage.php'>Products</a>";
    ?>
</div>

<div class="content">
    <h2>Add/Edit Product</h2>
    <?php if(isset($_SESSION["error"])): ?>
        <div class="error"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION["success"])): ?>
        <div class="success"><?php echo $_SESSION["success"]; unset($_SESSION["success"]); ?></div>
    <?php endif; ?>
    
    <form method="post" enctype="multipart/form-data">
        <?php if(isset($_GET['edit'])): 
            $edit_product = null;
            foreach ($products as $product) {
                if ($product['pk_itemID'] == $_GET['edit']) {
                    $edit_product = $product;
                    break;
                }
            }
            if ($edit_product): ?>
                <input type="hidden" name="edit_id" value="<?= $edit_product['pk_itemID'] ?>">
                <input type="hidden" name="existing_cover" value="<?= $edit_product['cover_image'] ?>">
                <p>Book Name: <input type="text" name="bookName" value="<?= htmlspecialchars($edit_product['name']) ?>" required></p>
                <p>Type: <input type="text" name="type" value="<?= htmlspecialchars($edit_product['type']) ?>"></p>
                <p>Price: <input type="number" step="0.01" name="price" value="<?= $edit_product['price'] ?>" required></p>
                <p>Stock: <input type="number" name="stock" value="<?= $edit_product['stock'] ?>"></p>
                <?php if ($edit_product['cover_image']): ?>
                    <p>Current Cover: <img src="uploads/<?= $edit_product['cover_image'] ?>" height="50"></p>
                <?php endif; ?>
                <p>Change Cover: <input type="file" name="cover"></p>
                <p><input type="submit" value="Update Product"></p>
            <?php endif; ?>
        <?php else: ?>
            <p>Book Name: <input type="text" name="bookName" required></p>
            <p>Type: <input type="text" name="type"></p>
            <p>Price: <input type="number" step="0.01" name="price" required></p>
            <p>Stock: <input type="number" name="stock" value="0"></p>
            <p>Cover Image: <input type="file" name="cover" required></p>
            <p><input type="submit" value="Add Product"></p>
        <?php endif; ?>
    </form>

    <h2>Product List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Cover</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= htmlspecialchars($product['pk_itemID']) ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= htmlspecialchars($product['type']) ?></td>
            <td>$<?= number_format($product['price'], 2) ?></td>
            <td><?= $product['stock'] ?></td>
            <td><img src="uploads/<?= htmlspecialchars($product['cover_image']) ?>" height="50" onerror="this.src='images/default.jpg'"></td>
            <td>
                <a href="ConfigurationPage.php?edit=<?= $product['pk_itemID'] ?>">Edit</a> | 
                <a href="ConfigurationPage.php?delete=<?= $product['pk_itemID'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include("footer.php"); ?>
</body>
</html>