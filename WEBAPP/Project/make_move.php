<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$game_id = $_POST['game_id'];
$position = $_POST['position'];
$player_id = $_SESSION['user_id'];

// Get game state
$stmt = $conn->prepare("SELECT current_turn, player1_id, player2_id, status, board_state FROM games WHERE id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

// Validate move
if ($game['status'] !== 'in_progress') {
    echo json_encode(['error' => 'Game is not active']);
    exit;
}

if ($game['current_turn'] != $player_id) {
    echo json_encode(['error' => 'Not your turn']);
    exit;
}

$symbol = ($player_id == $game['player1_id']) ? 'X' : 'O';
$board = str_split($game['board_state']);

if ($board[$position] !== '-') {
    echo json_encode(['error' => 'Cell already taken']);
    exit;
}

// Get move number
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM moves WHERE game_id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$move_number = $stmt->get_result()->fetch_assoc()['count'] + 1;

// Save move
$stmt = $conn->prepare("INSERT INTO moves (game_id, player_id, cell_position, symbol, move_number) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiisi", $game_id, $player_id, $position, $symbol, $move_number);
$stmt->execute();

// Update board
$board[$position] = $symbol;
$new_board = implode('', $board);

// Simple win check
$winPatterns = [
    [0,1,2], [3,4,5], [6,7,8], // rows
    [0,3,6], [1,4,7], [2,5,8], // columns
    [0,4,8], [2,4,6]           // diagonals
];

$winner = false;
foreach ($winPatterns as $pattern) {
    if ($board[$pattern[0]] === $symbol &&
        $board[$pattern[1]] === $symbol &&
        $board[$pattern[2]] === $symbol) {
        $winner = true;
        break;
    }
}

$is_draw = (strpos($new_board, '-') === false);

if ($winner) {
    $stmt = $conn->prepare("UPDATE games SET board_state = ?, status = 'completed', winner = ?, completed_at = NOW() WHERE id = ?");
    $stmt->bind_param("sii", $new_board, $player_id, $game_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'game_over' => true, 'winner' => $player_id]);
} elseif ($is_draw) {
    $stmt = $conn->prepare("UPDATE games SET board_state = ?, status = 'completed', winner = NULL, completed_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $new_board, $game_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'game_over' => true, 'draw' => true]);
} else {
    $next_turn = ($player_id == $game['player1_id']) ? $game['player2_id'] : $game['player1_id'];
    $stmt = $conn->prepare("UPDATE games SET board_state = ?, current_turn = ? WHERE id = ?");
    $stmt->bind_param("sii", $new_board, $next_turn, $game_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'game_over' => false]);
}
?>