<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$game_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$game_id) {
    header('Location: lobby.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

if (!$game) {
    header('Location: lobby.php');
    exit;
}

$is_player1 = ($game['player1_id'] == $_SESSION['user_id']);
$is_player2 = ($game['player2_id'] == $_SESSION['user_id']);

if (!$is_player1 && !$is_player2 && $game['player2_id'] !== null) {
    header('Location: lobby.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe - Game #<?php echo $game_id; ?></title>
    <script src="Jquery.js"></script>
    <script src="game.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="lobby.php" class="back-btn">← Back to Lobby</a>
    
    <div class="game-container">
        <div class="game-info">
             Game #<?php echo $game_id; ?>
        </div>
        <div id="turn-indicator">Loading...</div>
        <div id="game-board"></div>
    </div>
    
    <script>
        // Pass PHP variables to JavaScript
        const gameId = <?php echo $game_id; ?>;
        const currentPlayerId = <?php echo $_SESSION['user_id']; ?>;
    </script>
</body>
</html>