<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Lobby - Tic Tac Toe</title>
    <script src="Jquery.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="user-info">
         Welcome, <?php echo $_SESSION['username']; ?>!
    </div>
    
    <button class="logout-btn" onclick="logout()"> Logout</button>
    
    <div class="lobby-container">
        <h1> Tic Tac Toe - Game Lobby</h1>
        
        <button class="create-btn" onclick="createGame()"> Create New Game</button>
        
        <div class="game-list">
            <h3> Available Games:</h3>
            <div id="game-list">Loading...</div>
        </div>
    </div>
    
    <script>
        function createGame() {
            $.ajax({
                url: 'create_game.php',
                method: 'POST',
                success: function(data) {
                    if (data.game_id) {
                        window.location.href = 'game.php?id=' + data.game_id;
                    } else {
                        alert('Error creating game');
                    }
                }
            });
        }
        
        function joinGame(gameId) {
            $.ajax({
                url: 'join_game.php',
                method: 'POST',
                data: { game_id: gameId },
                success: function(data) {
                    if (data.success) {
                        window.location.href = 'game.php?id=' + gameId;
                    } else {
                        alert(data.error || 'Could not join game');
                    }
                }
            });
        }
        
        function logout() {
            window.location.href = 'logout.php';
        }
        
        function loadGames() {
            $.ajax({
                url: 'get_waiting_games.php',
                method: 'GET',
                success: function(games) {
                    const gameList = $('#game-list');
                    
                    if (games.length === 0) {
                        gameList.html('<div class="no-games">No games available. Create one!</div>');
                    } else {
                        let html = '';
                        games.forEach(function(game) {
                            html += `
                                <div class="game-item">
                                    <span> Game #${game.id} - Created by: ${game.creator}</span>
                                    <button class="join-btn" onclick="joinGame(${game.id})">Join Game</button>
                                </div>
                            `;
                        });
                        gameList.html(html);
                    }
                }
            });
        }
        
        // Load games initially and every 3 seconds
        loadGames();
        setInterval(loadGames, 3000);
    </script>
</body>
</html>