<?php
    include "../templates/functions.php";

    if (isset($_POST['player_id']) && isset($_POST['game_id'])
        && isset($_POST['x_coord']) && isset($_POST['y_coord']))
    {       
        $response = $client->takeSquare([
            'x' => htmlspecialchars($_POST['x_coord']),
            'y' => htmlspecialchars($_POST['y_coord']),
            'gid' => htmlspecialchars($_POST['game_id']),
            'pid' => htmlspecialchars($_POST['player_id'])
        ]);
        
        switch ($response->return)
        {
            case 'ERROR-TAKEN':
                $response = [
                    'error' => 'The square is already taken!'
                ];
                break;
            case 'ERROR-DB':
                $response = [
                    'error' => 'Error accessing the database!'
                ];
                break;
            case 'ERROR':
                $response = [
                    'error' => 'Something went wrong...'
                ];
                break;
            case 0: // Unsuccesful taking the square
                $response = [
                    'message' => -1
                ];
                break;
            case 1:
                // Check if game was won after move
                $response = $client->checkWin([
                    'gid' => htmlspecialchars($_POST['game_id'])   
                ]); // Check for a win after a square is taken
                switch ($response->return)
                {
                    case "ERROR-RETRIVE":
                        $response = [
                            'error' => "Square taken successfully, but an error occured retrieving data!"
                        ];
                        break;
                    case "ERROR-NOGAME":
                        $response = [
                            'error' => "Square taken successfully, but the game data has been lost!"
                        ];
                        break;
                    case "ERROR-DB":
                        $response = [
                            'error' => "Square taken successfully, but an issue occured in the database"
                        ];
                        break;
                    case 0: // Game is ongoing
                        $response = [
                            'message' => 0
                        ];
                        break;
                    case 1: // Player 1 won
                        $client->setGameState([
                            'gid' => htmlspecialchars($_POST['game_id']),
                            'gstate' => 1,
                        ]);
                        $response = [
                            'message' => 1,
                        ];
                        break;
                    case 2: // Player 2 won
                        $client->setGameState([
                            'gid' => htmlspecialchars($_POST['game_id']),
                            'gstate' => 2,
                        ]);
                        $response = [
                            'message' => 2,
                        ];
                        break;
                    case 3: // Draw
                        $client->setGameState([
                            'gid' => htmlspecialchars($_POST['game_id']),
                            'gstate' => 3
                        ]);
                        $response = [
                            'message' => 3
                        ];
                        break;
                }
                break;
        }
        // Send back JSON response
        echo json_encode($response);
    }
    else
        echo json_encode(['error' => "Invalid request"]);
?>
