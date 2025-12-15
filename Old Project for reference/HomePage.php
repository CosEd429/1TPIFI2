<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['pk_username'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
    <link href='https://fonts.googleapis.com/css?family=Baskervville SC' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Welcome - Portable Indoor Feedback</title>
</head>
<body>
<?php include("header.php"); ?>

<div class="menu">
    <h1>Welcome</h1>
    <a href="WelcomePage.php" class="active"><i class="fas fa-home"></i> Home</a>
    <a href="Login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
    <a href="Register.php"><i class="fas fa-user-plus"></i> Register</a>
</div>

<div class="content">
    <!-- HERO SECTION -->
    <div class="hero-section">
        <h1>Welcome to Portable Indoor Feedback</h1>
        <p class="subtitle">Monitor your indoor climate, share data with friends, and create a healthier living environment.</p>
        
        <div class="cta-buttons">
            <a href="Register.php" class="btn-primary btn-large">
                <i class="fas fa-user-plus"></i> Get Started - Register Now
            </a>
            <a href="Login.php" class="btn-secondary btn-large">
                <i class="fas fa-sign-in-alt"></i> Existing User? Login
            </a>
        </div>
    </div>

    <!-- FEATURES SECTION -->
    <div class="features-section">
        <h2>Why Choose Portable Indoor Feedback?</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-thermometer-half fa-3x"></i>
                </div>
                <h3>Real-time Monitoring</h3>
                <p>Track temperature, humidity, air pressure, light levels, and air quality in real-time from your portable stations.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line fa-3x"></i>
                </div>
                <h3>Data Analytics</h3>
                <p>View historical data, create collections, and analyze trends to optimize your indoor environment.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-share-alt fa-3x"></i>
                </div>
                <h3>Share with Friends</h3>
                <p>Create collections of measurements and share them with friends on the platform for collaborative monitoring.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt fa-3x"></i>
                </div>
                <h3>Easy to Use</h3>
                <p>Simple setup - just plug in your station, connect to WiFi, and start monitoring through our user-friendly website.</p>
            </div>
        </div>
    </div>

    <!-- HOW IT WORKS -->
    <div class="how-it-works">
        <h2>How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Register Your Account</h3>
                <p>Create a free account to get started with the platform.</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Register Your Station</h3>
                <p>Enter your station's serial number to connect it to your account.</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Monitor & Analyze</h3>
                <p>View real-time data and historical measurements from your dashboard.</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>Share & Collaborate</h3>
                <p>Create collections and share them with friends for collaborative analysis.</p>
            </div>
        </div>
        
        <!-- SINGLE CALL TO ACTION -->
        <div class="center">
            <a href="Register.php" class="btn-primary">
                <i class="fas fa-rocket"></i> Start Your Journey Today
            </a>
            <p style="margin-top: 15px; color: #666;">
                Already have an account? <a href="Login.php">Login here</a>
            </p>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>