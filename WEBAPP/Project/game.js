$(document).ready(function() {
    console.log("Game.js loaded");
    console.log("Game ID:", gameId);
    console.log("Player ID:", currentPlayerId);
    
    // Game state
    let mySymbol = null;
    let gameActive = true;
    let boardState = ["", "", "", "", "", "", "", "", ""];
    let pollingInterval = null;
    
    // Create the board
    function createBoard() {
        let table = $("</table>");
        
        for (let i = 0; i < 3; i++) {
            let row = $("<tr>");
            for (let j = 0; j < 3; j++) {
                const cellIndex = i * 3 + j;
                let cell = $("<td>");
                cell.attr("class", "cell");
                cell.attr("data-index", cellIndex);
                
                cell.on("click", function() {
                    if (!gameActive) {
                        alert("Game is over!");
                        return;
                    }
                    
                    if (boardState[cellIndex] !== "") {
                        alert("Cell already taken!");
                        return;
                    }
                    
                    makeMove(cellIndex);
                });
                
                row.append(cell);
            }
            table.append(row);
        }
        
        $("#game-board").append(table);
    }
    
    // Send move to server
    function makeMove(position) {
        $.ajax({
            url: 'make_move.php',
            method: 'POST',
            data: {
                game_id: gameId,
                position: position,
                player_id: currentPlayerId
            },
            dataType: 'json',
            success: function(data) {
                console.log("Move response:", data);
                if (data.success) {
                    boardState[position] = mySymbol;
                    $('.cell').eq(position).html(mySymbol);
                    
                    if (data.game_over) {
                        gameActive = false;
                        if (data.winner === currentPlayerId) {
                            alert("🎉 Congratulations! You won! 🎉");
                        } else if (data.draw) {
                            alert("🤝 Game ended in a draw!");
                        }
                        if (pollingInterval) clearInterval(pollingInterval);
                    }
                } else {
                    alert(data.error || "Invalid move!");
                }
            },
            error: function(xhr, status, error) {
                console.log("Move error - Status:", status);
                console.log("Move error - Response:", xhr.responseText);
                alert("Error making move. Check console.");
            }
        });
    }
    
    // Check game status from server
    function checkGameStatus() {
        console.log("Checking game status...");
        
        $.ajax({
            url: 'get_game_state.php',
            method: 'GET',
            data: { game_id: gameId },
            dataType: 'json',
            success: function(game) {
                console.log("Game state received:", game);
                
                if (!mySymbol) {
                    if (game.player1_id === currentPlayerId) {
                        mySymbol = "X";
                    } else if (game.player2_id === currentPlayerId) {
                        mySymbol = "O";
                    } else {
                        console.log("Waiting for second player to join...");
                        $("#turn-indicator").html("⏳ Waiting for opponent to join...");
                        return;
                    }
                }
                
                if (game.board_state !== boardState.join('')) {
                    console.log("Updating board from server");
                    boardState = game.board_state.split('');
                    $('.cell').each(function(index) {
                        const symbol = boardState[index];
                        if (symbol !== '-') {
                            $(this).html(symbol);
                        } else {
                            $(this).html('');
                        }
                    });
                }
                
                if (game.status === 'completed') {
                    gameActive = false;
                    if (pollingInterval) clearInterval(pollingInterval);
                    
                    if (game.winner === currentPlayerId) {
                        $("#turn-indicator").html("🎉 You won! 🎉");
                    } else if (game.winner === null) {
                        $("#turn-indicator").html("🤝 Game ended in a draw!");
                    } else {
                        $("#turn-indicator").html("😔 You lost! Better luck next time!");
                    }
                    return;
                }
                
                const isMyTurn = (game.current_turn === currentPlayerId);
                if (isMyTurn && gameActive) {
                    $("#turn-indicator").html(`🎯 Your turn! (${mySymbol})`);
                    $(".cell").css("cursor", "pointer");
                } else if (gameActive) {
                    $("#turn-indicator").html("⏳ Waiting for opponent...");
                    $(".cell").css("cursor", "not-allowed");
                }
            },
            error: function(xhr, status, error) {
                console.log("Error checking game status!");
                console.log("Status:", status);
                console.log("Response text:", xhr.responseText);
                console.log("Error object:", error);
                $("#turn-indicator").html(" Error connecting to server. Check console.");
            }
        });
    }
    
    createBoard();
    checkGameStatus();
    pollingInterval = setInterval(checkGameStatus, 2000);
});