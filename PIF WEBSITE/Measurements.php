<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['pk_username'])) {
    header("Location: Login.php");
    exit();
}

$username = $_SESSION['pk_username'];

// Get user's stations for dropdown
$sql = "SELECT pk_serialNumber, name FROM station WHERE fk_user_owns = ? ORDER BY name";
if ($_SESSION['admin']) {
    // Admins can see all stations
    $sql = "SELECT pk_serialNumber, name FROM station ORDER BY name";
}

$stmt = mysqli_prepare($conn, $sql);
if ($_SESSION['admin']) {
    mysqli_stmt_execute($stmt);
} else {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
}
$stations_result = mysqli_stmt_get_result($stmt);

// Get selected station from GET parameter or default
$selected_station = $_GET['station'] ?? '';
$date_from = $_GET['date_from'] ?? date('Y-m-d', strtotime('-7 days'));
$date_to = $_GET['date_to'] ?? date('Y-m-d');
$time_from = $_GET['time_from'] ?? '00:00';
$time_to = $_GET['time_to'] ?? '23:59';

// Initialize measurements array
$measurements = [];
$measurement_count = 0;
$station_name = "Unnamed Station";

// Only query if a station is selected
if (!empty($selected_station)) {
    // Verify user has access to this station
    $check_sql = "SELECT pk_serialNumber FROM station WHERE pk_serialNumber = ?";
    if (!$_SESSION['admin']) {
        $check_sql .= " AND fk_user_owns = ?";
    }
    
    $check_stmt = mysqli_prepare($conn, $check_sql);
    if ($_SESSION['admin']) {
        mysqli_stmt_bind_param($check_stmt, "s", $selected_station);
    } else {
        mysqli_stmt_bind_param($check_stmt, "ss", $selected_station, $username);
    }
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        // User has access - get measurements
        $datetime_from = $date_from . ' ' . $time_from . ':00';
        $datetime_to = $date_to . ' ' . $time_to . ':59';
        
        $measure_sql = "SELECT * FROM measurement 
                       WHERE fk_station_records = ? 
                       AND timestamp BETWEEN ? AND ?
                       ORDER BY timestamp DESC 
                       LIMIT 100";
        
        $measure_stmt = mysqli_prepare($conn, $measure_sql);
        mysqli_stmt_bind_param($measure_stmt, "sss", $selected_station, $datetime_from, $datetime_to);
        mysqli_stmt_execute($measure_stmt);
        $measurements_result = mysqli_stmt_get_result($measure_stmt);
        $measurement_count = mysqli_num_rows($measurements_result);
        
        // Fetch all measurements into array
        while($row = mysqli_fetch_assoc($measurements_result)) {
            $measurements[] = $row;
        }
        mysqli_stmt_close($measure_stmt);
        
        // Get station name for display
        $name_sql = "SELECT name FROM station WHERE pk_serialNumber = ?";
        $name_stmt = mysqli_prepare($conn, $name_sql);
        mysqli_stmt_bind_param($name_stmt, "s", $selected_station);
        mysqli_stmt_execute($name_stmt);
        mysqli_stmt_bind_result($name_stmt, $db_station_name);
        if (mysqli_stmt_fetch($name_stmt)) {
            $station_name = $db_station_name ?: 'Unnamed Station';
        }
        mysqli_stmt_close($name_stmt);
    }
    mysqli_stmt_close($check_stmt);
}

// Reset stations result pointer for dropdown
mysqli_data_seek($stations_result, 0);
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
    <title>Measurements</title>
</head>
<body>
<?php include("header.php"); ?>

<div class="menu">
    <h1>Measurements</h1>
    <a href="WelcomePage.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="Stations.php"><i class="fas fa-thermometer-half"></i> Stations</a>
    <a href="Measurements.php" class="active"><i class="fas fa-chart-line"></i> Measurements</a>
    <a href="Collections.php"><i class="fas fa-folder"></i> Collections</a>
    <a href="Friends.php"><i class="fas fa-users"></i> Friends</a>
    <a href="Profile.php"><i class="fas fa-user"></i> Profile</a>
    <?php if ($_SESSION['admin']): ?>
        <a href="AdminConfiguration.php"><i class="fas fa-cog"></i> Admin</a>
    <?php endif; ?>
    <a href="Logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">
    <h2>View Measurements</h2>
    
    <!-- Success/Error Messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    
    <!-- Filter Card -->
    <div class="dashboard-card">
        <h3><i class="fas fa-filter"></i> Filter Measurements</h3>
        <form method="GET" action="Measurements.php" class="measurement-form">
            <div class="form-group">
                <label for="station">Select Station</label>
                <select id="station" name="station" required>
                    <option value="">-- Select a Station --</option>
                    <?php while($station = mysqli_fetch_assoc($stations_result)): ?>
                        <option value="<?php echo htmlspecialchars($station['pk_serialNumber']); ?>"
                            <?php echo ($selected_station == $station['pk_serialNumber']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($station['name'] ?: $station['pk_serialNumber']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_from">From Date</label>
                    <input type="date" id="date_from" name="date_from" 
                           value="<?php echo htmlspecialchars($date_from); ?>">
                </div>
                
                <div class="form-group">
                    <label for="time_from">Time</label>
                    <input type="time" id="time_from" name="time_from" 
                           value="<?php echo htmlspecialchars($time_from); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_to">To Date</label>
                    <input type="date" id="date_to" name="date_to" 
                           value="<?php echo htmlspecialchars($date_to); ?>">
                </div>
                
                <div class="form-group">
                    <label for="time_to">Time</label>
                    <input type="time" id="time_to" name="time_to" 
                           value="<?php echo htmlspecialchars($time_to); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <input type="submit" value="View Data" class="btn-primary">
                <a href="Measurements.php" class="btn-secondary">Clear Filters</a>
            </div>
        </form>
    </div>
    
    <!-- Data Table Card -->
    <div class="dashboard-card">
        <h3>
            <i class="fas fa-table"></i> 
            <?php if (!empty($selected_station)): ?>
                Measurements for <?php echo htmlspecialchars($station_name); ?> 
                (<?php echo $measurement_count; ?> found)
            <?php else: ?>
                Measurement Data
            <?php endif; ?>
        </h3>
        
        <?php if (!empty($selected_station)): ?>
            <?php if ($measurement_count > 0): ?>
                <div class="table-container">
                    <table class="measurement-table">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Temperature (°C)</th>
                                <th>Humidity (%)</th>
                                <th>Pressure (hPa)</th>
                                <th>Light (lux)</th>
                                <th>Gas (ppm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($measurements as $measurement): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($measurement['timestamp']); ?></td>
                                    <td><?php echo htmlspecialchars($measurement['temperature']); ?></td>
                                    <td><?php echo htmlspecialchars($measurement['humidity']); ?></td>
                                    <td><?php echo htmlspecialchars($measurement['pressure']); ?></td>
                                    <td><?php echo htmlspecialchars($measurement['light']); ?></td>
                                    <td><?php echo htmlspecialchars($measurement['gas']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($measurement_count == 100): ?>
                    <p class="small-note">Showing latest 100 measurements. Use filters to see more.</p>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-search fa-3x"></i>
                    <p>No measurements found for the selected filters</p>
                    <p>Try adjusting the date range or select a different station</p>
                    <p><strong>Debug Tips:</strong></p>
                    <ul style="text-align: left; max-width: 500px; margin: 0 auto;">
                        <li>Check if station <?php echo htmlspecialchars($selected_station); ?> exists</li>
                        <li>Date range: <?php echo htmlspecialchars($date_from); ?> to <?php echo htmlspecialchars($date_to); ?></li>
                        <li>Station belongs to: <?php echo htmlspecialchars($username); ?></li>
                    </ul>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-thermometer fa-3x"></i>
                <p>Select a station to view measurements</p>
                <p>Choose a station from the dropdown above and click "View Data"</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>