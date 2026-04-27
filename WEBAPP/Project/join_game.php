<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$game_id = $_POST['game_id'];
$player2_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    UPDATE games 
    SET player2_id = ?, 
        status = 'in_progress', 
        current_turn = ? 
    WHERE id = ? 
    AND status = 'waiting' 
    AND player2_id IS NULL
");
$stmt->bind_param("iii", $player2_id, $player2_id, $game_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Game no longer available']);
}
?>