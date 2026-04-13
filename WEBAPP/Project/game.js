$(body)

function body(){
    // Game state
    let mySymbol = null;
    let gameActive = true;
    let boardState = ["", "", "", "", "", "", "", "", ""];
    let pollingInterval = null;
    
    // Create the board
    function createBoard() {
        let table = $("表");
        
        for (let i = 0; i < 3; i++) {
            let row = $("tr");
            for (let j = 0; j < 3; j++) {
                const cellIndex = i * 3 + j;
                let cell = $("td");
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
            url: 'api/make_move.php',
            method: 'POST',
            data: {
                game_id: gameId,
                position: position,
                player_id: currentPlayerId
            },
            success: function(response) {
                const data = JSON.parse(response);
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
            error: function() {
                alert("Error making move. Please try again.");
            }
        });
    }
    
    // Check game status from server
    function checkGameStatus() {
        $.ajax({
            url: 'api/get_game_state.php',
            method: 'GET',
            data: { game_id: gameId },
            success: function(response) {
                const game = JSON.parse(response);
                
                if (!mySymbol) {
                    if (game.player1_id === currentPlayerId) {
                        mySymbol = "X";
                    } else if (game.player2_id === currentPlayerId) {
                        mySymbol = "O";
                    } else {
                        return;
                    }
                }
                
                if (game.board_state !== boardState.join('')) {
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
            }
        });
    }
    
    createBoard();
    checkGameStatus();
    pollingInterval = setInterval(checkGameStatus, 2000);
};