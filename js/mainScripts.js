// Wait until all content has loaded
window.document.addEventListener("DOMContentLoaded", function()
{
    var element; // For checking if element exists
    
    // Function definitions
    function ajax(message, destination, onFinish)
    {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function(event)
        {
            if (this.readyState === 4 && this.status === 200)
                onFinish(this.responseText); // Call function with response
        };
        
        request.open("POST", destination, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(message);
    }
    
    // Redirect to registration when sign up button is clicked
    element = document.getElementById("login-sign-up");
    if (element !== null)
        element.onclick = function ()
        {
            location.href = "/PHPClient/register.php";
        };
                
    // Cancel registration, so redirect back to login
    element = document.getElementById("register-cancel");
    if (element !== null)
        element.onclick = function ()
        {
            location.href = "/PHPClient/index.php";
        };
        
    // Create new game
    element = document.getElementById("create-game");
    if (element !== null)
        element.onclick = function ()
        {
            ajax("player_id=" + _player_id, "actions/newGame.php", function (message)
            {
                let parsedMessage = JSON.parse(message);
                // Create form element
                element = document.createElement("form");
                element.method = "POST";
                element.action = "game.php";
                //element.target = "_blank";
                
                // Append game id
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = "ttt_game_id";
                input.value = parsedMessage['game_id'];
                element.appendChild(input);
                
                // Append player id
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = "ttt_player_id";
                input.value = _player_id;
                element.appendChild(input);
                
                // submit
                document.body.appendChild(element); // Append to body
                element.submit();
            });
        };
        
    // Update open games list
    element = document.getElementById("open-games-table");
    if (element !== null)
    {
        function pollOpenGames()
        {
            ajax("_game_request=true", "actions/openGames.php", function (response)
            {
                element = document.getElementById("open-games-table");
                element.innerHTML = ""; // Clear contents
                let parsedResponse = JSON.parse(response);
                if ("error" in parsedResponse)
                    element.innerHTML = parsedResponse["error"];
                else // Games list was not empty
                {
                    let game_titles = [
                        " needs company in their Maritime Funtime Land",
                        " needs you to be the moon to their earth",
                        " currently is an X without an O",
                        " is engaging FTL: Faster Than Literacy",
                        " is bored, go annoy them!",
                        " === in need of a friend",
                        "'s a bit lonely, will you join them?",
                        " needs some love, go say hi!"
                    ];
                    let options = {
                            hour: 'numeric',
                            minute: 'numeric',
                            second: 'numeric',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            era: 'long'
                        };
                    let date = null;
                    let row_div = null;
                    let child = null;
                    for (let key in parsedResponse)
                    {
                        // Elements
                        row_div = document.createElement("div");
                        row_div.setAttribute('class', "open-game-row");
                        
                        // Host
                        child = document.createElement("div");
                        child.setAttribute('class', "game-host");
                        child.innerHTML = parsedResponse[key]['host'];
                        row_div.append(child);
                        
                        // Title
                        child = document.createElement("div");
                        child.setAttribute('class', "game-title");
                        child.innerHTML = parsedResponse[key]['host']
                            + game_titles[Math.floor(Math.random() * game_titles.length)];
                        row_div.append(child);
                        
                        // Game Creation Date
                        child = document.createElement("div");
                        child.setAttribute('class', "game-start");
                        date = new Date(Date.parse(parsedResponse[key]['date-created']));
                        child.innerHTML = date.toLocaleDateString("en-GB", options);
                        row_div.append(child);
                        
                        // Join Button
                        child = document.createElement("button");
                        child.setAttribute('class', "game-join-button");
                        child.innerHTML = "Join";
                        child.addEventListener('click', function (event)
                        {
                            ajax("_player_id=" +  _player_id + "&_game_id=" + parsedResponse[key]['id'],
                                "actions/joinGame.php", function (response)
                            {
                                parsedResponse = JSON.parse(response);
                                if ('error' in parsedResponse)
                                    alert("An error occured: " + response['error']);
                                else
                                {
                                    switch (parsedResponse['message'])
                                    {
                                        case 0: // Unsuccessful to join
                                            alert(parsedResponse['message']);
                                            break;
                                        case 1: // Joined successfully
                                            // Create form element
                                            let form = document.createElement("form");
                                            form.method = "POST";
                                            form.action = "game.php";
                                            
                                            // Append game id
                                            let input = document.createElement('input');
                                            input.type = 'hidden';
                                            input.name = "ttt_game_id";
                                            input.value = parsedResponse['game_id'];
                                            form.appendChild(input);

                                            // Append player id
                                            input = document.createElement('input');
                                            input.type = 'hidden';
                                            input.name = "ttt_player_id";
                                            input.value = _player_id;
                                            form.appendChild(input);
                                            
                                            // Join as second player
                                            input = document.createElement('input');
                                            input.type = 'hidden';
                                            input.name = "ttt_second_player";
                                            input.value = true;
                                            form.appendChild(input);
                                            
                                            // submit
                                            document.body.appendChild(form); // Append to body
                                            form.submit();
                                            break;
                                    }
                                }
                            });
                        });
                        row_div.append(child);
                        element.appendChild(row_div); // Append row
                    }
                }
            });
        };
        pollOpenGames(); // Ensure a check is made at the start
        var openGameTimer = window.setInterval(pollOpenGames, 10000); // Poll every 10 seconds
    }
    
    // poll for game state
    element = document.getElementById("game-grid");
    if (element !== null)
    {
        confirmNavigationDialogue(); // Enable navigation confirmatino dialogue
        element = document.getElementById("game-status-message");
        currentPlayerMove = true; // Keep track of which player's turn it is
        gameStarted = false;
        
        function updateUI(parsedResponse)
        {
            let cells = document.getElementsByClassName("game-grid-cell");
            let moves = parsedResponse['moves'];
            let image_element = null;
            for (let i = 0; i < moves.length; i++)
            {
                for (let j = 0; j < cells.length; j++)
                {
                    if (moves[i]['x'] == cells[j].getAttribute('x')
                            && moves[i]['y'] == cells[j].getAttribute('y'))
                    {
                        image_element = document.createElement("img");
                        image_element.setAttribute('class', "game-icon");
                        if (moves[i]['player_id'] == _player_id)
                            image_element.setAttribute('src', "resources/game_X.png");
                        else
                            image_element.setAttribute('src', "resources/game_O.png");
                        cells[j].innerHTML = ""; // Clear contents
                        cells[j].appendChild(image_element);
                    }
                }
            }
        }
            
        function pollGameState()
        {
            ajax("ttt_game_id=" + _game_id, "actions/updateGame.php", function (response)
            {
                element = document.getElementById("game-status-message");
                let parsedResponse = JSON.parse(response);
                if ("error" in parsedResponse)
                {
                    element.innerHTML = parsedResponse['error'];
                    if ('waiting' in parsedResponse)
                    {
                        console.log("This is gay!");
                        (document.getElementsByClassName("loader"))[0].style['display'] = "none";
                        gameStarted = true;
                    }
                }
                else // Games list was not empty
                {
                    switch (parsedResponse['message'])
                    {
                        case -1: // Waiting for other player
                            element.innerHTML = "Waiting for an opponenet to join...";
                            break;
                        case 0: // Game ongoing
                            gameStarted = true;
                            // Update status
                            updateUI(parsedResponse);
                            if (parsedResponse['last'] == _player_id) // Current player went last
                            {
                                currentPlayerMove = false;
                                element.innerHTML = "Waiting for opponent's turn!";
                                document.getElementsByClassName('loader')[0].style['display'] = "block";
                            }
                            else
                            {
                                currentPlayerMove = true;
                                element.innerHTML = "Your turn!";
                                document.getElementsByClassName('loader')[0].style['display'] = "none";
                            }
                            break;
                        case 1: // Player 1 won
                        case 2: // Player 2 won
                            if (parsedResponse['winner'] == _player_id)
                                element.innerHTML = "Congratumulations! You wan!";
                            else
                                element.innerHTML = "Gid Gud Skrub, you done goofed";
                            updateUI(parsedResponse);
                            document.getElementsByClassName('loader')[0].style['display'] = "none";
                            break;
                        case 3: // Draw
                            updateUI(parsedResponse);
                            element.innerHTML = "It's a draw! Try Rock-Paper-Scissors next?";
                            document.getElementsByClassName('loader')[0].style['display'] = "none";
                            break;
                    }
                }
            });
        }
        pollGameState();
        var gameStateTimer = window.setInterval(pollGameState, 1000); // Every 1 seconds
        
        // Attach click listener to each cell
        let cells = document.getElementsByClassName("game-grid-cell");
        for (let i = 0; i < cells.length; i++)
            cells[i].addEventListener('click', function (event)
            {
                if (!currentPlayerMove)
                    element.innerHTML = "It is not your turn!";
                else if (!gameStarted)
                    element.innerHTML = "Nice try, but you need an opponent";
                else
                    ajax(
                        "player_id=" + _player_id
                        + "&game_id=" + _game_id
                        + "&x_coord=" + event.target.getAttribute("x")
                        + "&y_coord=" + event.target.getAttribute("y"),
                        "actions/takeSquare.php",
                        function (response)
                        {
                            /* Note: The contents of this function are redundant now */
                            let parsedResponse = JSON.parse(response);
                            //let status_element = document.getElementById("game-status-message");
                            if ("error" in parsedResponse)
                                element.innerHTML = parsedResponse['error'];
                            else
                            {
                                switch (parsedResponse['message'])
                                {
                                    // TODO: Game win checks here
                                    case -1:
                                        element.innerHTML = "Square could not be taken!";
                                        break;
                                    case 0:
                                        element.innerHTML = "Square taken successfully!";
                                        break;
                                    case 1:
                                        element.innerHTML = "Congratulations, you have won!";
                                        break;
                                    case 2:
                                        element.innerHTML = "Unfortunately, you have lost!";
                                        break;
                                    case 3:
                                        element.innerHTML = "It's a draw!";
                                        break;
                                }
                            }
                        }
                    );
            });
    }
    
    // Open new window showing leaderboard
    element = document.getElementById("leaderboard");
    if (element !== null)
    {
        element.addEventListener('click', function (event)
        {
            window.open("/PHPClient/leaderboard.php");
        });
    }
    
    // Open new window showing player's scores
    element = document.getElementById("my-scores");
    if (element !== null)
    {
        element.addEventListener('click', function (event)
        {
            window.open("/PHPClient/scores.php");
        });
    }
    
    // Open new window showing player's open games
    element = document.getElementById("my-open-games");
    if (element !== null)
    {
        element.addEventListener('click', function (event)
        {
            window.open("/PHPClient/open-games.php");
        });
    }
        
    // Confirm if user wants to navigate away
    function confirmNavigationDialogue()
    {
        window.addEventListener('beforeunload', function (event)
        {
            // Can't be null
            event.returnValue = "Are you sure you want to navigate away from this page?";
            return event.returnValue;
        });
    }
});