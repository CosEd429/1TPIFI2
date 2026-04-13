<?php
session_start();
require_once '../db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$player1_id = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO games (player1_id, status, board_state) VALUES (?, 'waiting', '---------')");
$stmt->bind_param("i", $player1_id);
$stmt->execute();

echo json_encode(['game_id' => $conn->insert_id]);
?>