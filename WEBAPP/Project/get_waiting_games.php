<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

$stmt = $conn->prepare("
    SELECT g.id, u.username as creator 
    FROM games g 
    JOIN users u ON g.player1_id = u.id 
    WHERE g.status = 'waiting' 
    AND g.player2_id IS NULL
");
$stmt->execute();
$result = $stmt->get_result();

$games = [];
while ($row = $result->fetch_assoc()) {
    $games[] = $row;
}

echo json_encode($games);
?>