<?php
session_start();
require_once 'db.php';
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
    
    <title>Portable Indoor Feedback</title>
</head>
<body>
<?php include("header.php"); ?>

<?php if (isset($_SESSION['pk_username'])): ?>
    <!-- ========== LOGGED IN USER: SHOW DASHBOARD WITH NAV ========== -->
    <div class="menu">
        <h1>Dashboard</h1>
        <a href="WelcomePage.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="Stations.php"><i class="fas fa-thermometer-half"></i> My Stations</a>
        <a href="Measurements.php"><i class="fas fa-chart-line"></i> Measurements</a>
        <a href="Collections.php"><i class="fas fa-folder"></i> Collections</a>
        <a href="Friends.php"><i class="fas fa-users"></i> Friends</a>
        <a href="Profile.php"><i class="fas fa-user"></i> Profile</a>
        <?php if ($_SESSION['admin']): ?>
            <a href="AdminConfiguration.php"><i class="fas fa-cog"></i> Admin</a>
        <?php endif; ?>
        <a href="Logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <!-- WELCOME SECTION -->
        <div class="welcome-section">
            <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['firstName']); ?>!</h2>
            <p>This is your dashboard where you can monitor your stations, view measurements, and share data with friends.</p>
        </div>

        <!-- QUICK STATS SECTION -->
        <div class="stats-section">
            <h3><i class="fas fa-chart-bar"></i> Quick Overview</h3>
            
            <div class="stats-grid">
                <?php
                // Get user's station count
                $username = $_SESSION['pk_username'];
                $station_sql = "SELECT COUNT(*) as station_count FROM station WHERE fk_user_owns = ?";
                $stmt = mysqli_prepare($conn, $station_sql);
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $station_count);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
                ?>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-thermometer-half fa-2x"></i>
                    </div>
                    <div class="stat-info">
                        <h4>My Stations</h4>
                        <p class="stat-number"><?php echo $station_count; ?></p>
                        <a href="Stations.php" class="stat-link">View Stations →</a>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Recent Measurements</h4>
                        <p class="stat-number">Coming Soon</p>
                        <a href="Measurements.php" class="stat-link">View Data →</a>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-folder fa-2x"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Collections</h4>
                        <p class="stat-number">0</p>
                        <a href="Collections.php" class="stat-link">Manage →</a>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Friends</h4>
                        <p class="stat-number">0</p>
                        <a href="Friends.php" class="stat-link">Manage →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- DASHBOARD GRID -->
        <div class="dashboard-grid">
            <!-- LEFT COLUMN -->
            <div class="dashboard-column">
                <!-- Recent Measurements -->
                <div class="dashboard-card">
                    <h3><i class="fas fa-clock"></i> Recent Activity</h3>
                    <div class="recent-measurements">
                        <?php if ($station_count > 0): ?>
                            <p>You have <?php echo $station_count; ?> station(s) registered.</p>
                            <a href="Measurements.php" class="btn-primary">View All Measurements</a>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-thermometer-empty fa-3x"></i>
                                <p>No stations registered yet</p>
                                <a href="Stations.php" class="btn-primary">Register Your First Station</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="dashboard-card">
                    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                    <div class="quick-actions">
                        <a href="Stations.php?action=register" class="quick-action">
                            <i class="fas fa-plus-circle"></i>
                            <span>Register New Station</span>
                        </a>
                        <a href="Collections.php?action=create" class="quick-action">
                            <i class="fas fa-folder-plus"></i>
                            <span>Create Collection</span>
                        </a>
                        <a href="Friends.php?action=add" class="quick-action">
                            <i class="fas fa-user-plus"></i>
                            <span>Add Friend</span>
                        </a>
                        <a href="Measurements.php" class="quick-action">
                            <i class="fas fa-search"></i>
                            <span>Search Measurements</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="dashboard-column">
                <!-- System Status -->
                <div class="dashboard-card">
                    <h3><i class="fas fa-info-circle"></i> System Status</h3>
                    <div class="status-list">
                        <div class="status-item status-ok">
                            <i class="fas fa-check-circle"></i>
                            <span>Authentication: Active</span>
                        </div>
                        <div class="status-item status-ok">
                            <i class="fas fa-check-circle"></i>
                            <span>Database: Connected</span>
                        </div>
                        <div class="status-item <?php echo ($station_count > 0) ? 'status-ok' : 'status-warning'; ?>">
                            <i class="fas <?php echo ($station_count > 0) ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                            <span>Stations: <?php echo ($station_count > 0) ? 'Registered' : 'Not Registered'; ?></span>
                        </div>
                        <div class="status-item status-info">
                            <i class="fas fa-sync"></i>
                            <span>Last Update: <?php echo date('Y-m-d H:i:s'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="dashboard-card">
                    <h3><i class="fas fa-lightbulb"></i> Getting Started</h3>
                    <div class="tips-list">
                        <div class="tip">
                            <strong>1. Register a Station</strong>
                            <p>Use the serial number from your Portable Indoor Feedback device.</p>
                        </div>
                        <div class="tip">
                            <strong>2. View Measurements</strong>
                            <p>Check temperature, humidity, air quality from your stations.</p>
                        </div>
                        <div class="tip">
                            <strong>3. Share with Friends</strong>
                            <p>Create collections and share them with friends.</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Section -->
                <?php if ($_SESSION['admin']): ?>
                <div class="dashboard-card admin-notice">
                    <h3><i class="fas fa-crown"></i> Administrator</h3>
                    <p>You have administrator privileges.</p>
                    <a href="AdminConfiguration.php" class="btn-admin">Go to Admin Panel</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- ========== LOGGED OUT USER: SHOW WELCOME PAGE ========== -->
    <div class="content">
        <!-- HERO SECTION -->
        <div class="hero-section">
            <h1>Welcome to Portable Indoor Feedback</h1>
            <p class="subtitle">Monitor your indoor climate, share data with friends, and create a healthier living environment.</p>
            
            <div class="cta-buttons">
                <a href="Register.php" class="btn-primary btn-large">
                    Get Started - Register Now
                </a>
                <a href="Login.php" class="btn-secondary btn-large">
                    Existing User? Login
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
<?php endif; ?>

<?php include("footer.php"); ?>
</body>
</html>