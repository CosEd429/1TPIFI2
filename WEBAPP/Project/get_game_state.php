<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

$game_id = $_GET['game_id'];

$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'board_state' => $game['board_state'],
    'status' => $game['status'],
    'current_turn' => $game['current_turn'],
    'player1_id' => $game['player1_id'],
    'player2_id' => $game['player2_id'],
    'winner' => $game['winner']
]);
?>