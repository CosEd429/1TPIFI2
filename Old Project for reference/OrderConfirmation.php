<?php
session_start();

if(!isset($_SESSION["username"])) {
    header("Location: Login-form.php");
    exit;
}

if(!isset($_SESSION["order_success"])) {
    header("Location: ProductsPage.php");
    exit;
}

$message = $_SESSION["order_success"];
unset($_SESSION["order_success"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
    <link href='https://fonts.googleapis.com/css?family=Baskervville SC' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include("header.php"); ?>

<div class="menu">
    <h1>Order Confirmation</h1>
    <?php
    echo "<a href='HomePage.php'>HomePage</a>";
    if(isset($_SESSION["username"])) {
        echo "<a href='Logout.php'>Logout</a>";
        echo "<a href='ProductsPage.php'>Products</a>";
        if($_SESSION["admin"]) {
            echo "<a href='ConfigurationPage.php'>Configuration</a>";
        }
    } else {
        echo "<a href='Login-form.php'>Login</a>";
        echo "<a href='Sign-up.php'>Sign Up</a>";
    }
    ?>
</div>

<div class="content">
    <div class="confirmation-message">
        <h2><?php echo htmlspecialchars($message); ?></h2>
        <p>Thank you for your order!</p>
        <a href="ProductsPage.php" class="button">Continue Shopping</a>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>