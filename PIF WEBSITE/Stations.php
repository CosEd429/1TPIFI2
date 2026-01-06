<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['pk_username'])) {
    header("Location: Login.php");
    exit();
}

$username = $_SESSION['pk_username'];

// Get user's stations
$sql = "SELECT * FROM station WHERE fk_user_owns = ? ORDER BY name";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$stations = mysqli_stmt_get_result($stmt);
$station_count = mysqli_num_rows($stations);

// Get station data for display
$station_data = [];
while($station = mysqli_fetch_assoc($stations)) {
    $station_data[] = $station;
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
    <script src="script.js" defer></script>
    <title>My Stations</title>
</head>
<body>
<?php include("header.php"); ?>

<div class="menu">
    <h1>My Stations</h1>
    <a href="WelcomePage.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="Stations.php" class="active"><i class="fas fa-thermometer-half"></i> Stations</a>
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
    <h2>Manage Your Stations</h2>
    
    <!-- Success/Error Messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <!-- Register New Station Card -->
    <div class="dashboard-card">
        <h3><i class="fas fa-plus-circle"></i> Register New Station</h3>
        <form method="POST" action="AuthenticationPage.php" class="station-form">
            <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <input type="text" id="serial_number" name="serial_number" 
                       placeholder="e.g., SN-1001">
                <small>Enter the serial number from your Portable Indoor Feedback device</small>
            </div>
            <div class="form-group">
                <input type="submit" name="register_station" value="Register Station" class="btn-primary">
            </div>
        </form>
    </div>
    
    <!-- Stations List Card -->
    <div class="dashboard-card">
        <h3><i class="fas fa-thermometer-half"></i> Your Stations (<?php echo $station_count; ?>)</h3>
        
        <?php if ($station_count > 0): ?>
            <div class="stations-list">
                <?php foreach($station_data as $station): ?>
                    <div class="station-item">
                        <div class="station-info">
                            <h4><?php echo htmlspecialchars($station['name'] ?: 'Unnamed Station'); ?></h4>
                            <div class="station-details">
                                <span class="serial">Serial: <?php echo htmlspecialchars($station['pk_serialNumber']); ?></span>
                                <?php if($station['description']): ?>
                                    <p class="description"><?php echo htmlspecialchars($station['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="station-actions">
                            <!-- Edit Button (opens modal) -->
                            <button class="btn-edit" onclick="openEditModal(
                                '<?php echo $station['pk_serialNumber']; ?>',
                                '<?php echo addslashes($station['name'] ?? ''); ?>',
                                '<?php echo addslashes($station['description'] ?? ''); ?>'
                            )">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            
                            <!-- View Measurements Link -->
                            <a href="Measurements.php?station=<?php echo urlencode($station['pk_serialNumber']); ?>" class="btn-view">
                                <i class="fas fa-chart-line"></i> View Data
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-thermometer-empty fa-3x"></i>
                <p>No stations registered yet</p>
                <p>Register a station using the form above</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Station Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Edit Station</h3>
        <form method="POST" action="AuthenticationPage.php">
            <input type="hidden" id="edit_serial" name="station_serial">
            
            <div class="form-group">
                <label for="edit_name">Station Name</label>
                <input type="text" id="edit_name" name="station_name" 
                       placeholder="e.g., Living Room Station">
            </div>
            
            <div class="form-group">
                <label for="edit_description">Description</label>
                <textarea id="edit_description" name="station_description" 
                         rows="3" placeholder="Optional description..."></textarea>
            </div>
            
            <div class="form-group">
                <input type="submit" name="update_station" value="Save Changes" class="btn-primary">
            </div>
        </form>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>