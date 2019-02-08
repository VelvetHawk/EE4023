<?php
    include_once "../templates/functions.php";
    
    function updateUI($client)
    {
        $response = $client->getBoard(
            ['gid' => $_POST['ttt_game_id']]
        );
        $moves = [];
        foreach (explode("\n", $response->return) as $move)
        {
            $entity = explode(",", $move);
            array_push($moves, [
                'player_id' => $entity[0],
                'x' => $entity[1],
                'y' => $entity[2]
            ]);
        }
        return [
            'moves' => $moves,
            'last' => $moves[sizeof($moves)-1]['player_id']// Last player to move
        ];
    }
    
    if (isset($_POST['ttt_game_id']))
    {
        // Clean POST
        $_POST['ttt_game_id'] = htmlspecialchars($_POST['ttt_game_id']);
        
        $response = $client->getGameState(
            ['gid' => $_POST['ttt_game_id']]
        );
        
        switch($response->return)
        {
            case 'ERROR-NOGAME':
                $response = [
                    'error' => "Game could not be found!"
                ];
                break;
            case 'ERROR-DB':
                $response = [
                    'error' => "Error connecting to the database!"
                ];
                break;
            case -1: // Waiting for second player
                // Do nothing
                $response = [
                    'message' => -1
                ];
                break;
            case 0: // Game in progress
                // Poll DB for last move
                $response = $client->getBoard(
                    ['gid' => $_POST['ttt_game_id']]
                );
                // Check the moves so far for this game
                switch ($response->return)
                {
                    case 'ERROR-NOMOVES':
                        $response = [
                            'error' => "Waiting for a player to go first!",
                            'waiting' => true
                        ];
                        break;
                    case 'ERROR-DB':
                        $response = [
                            'error' => "Game found, but connection was interupted while accessing the database!"
                        ];
                        break;
                    default:
                        // Check who's turn it is
                        $return = updateUI($client);
                        $response = [
                            'message' => 0,
                            'moves' => $return['moves'],
                            'last' => $return['last']
                        ];
                        break;
                }
                break;
            case 1: // Player one won
                $return = updateUI($client);
                $response = [
                    'message' => 1,
                    'winner' => $return['last'],
                    'moves' => $return['moves']
                ];
                break;
            case 2: // Player two won
                $return = updateUI($client);
                $response = [
                    'message' => 2,
                    'winner' => $return['last'],
                    'moves' => $return['moves']
                ];
                break;
            case 3: // Draw
                $return = updateUI($client);
                $response = [
                    'message' => 3,
                    'moves' => $return['moves']
                ];
                break;
        }
        
        // Send back as JSON response
        echo json_encode($response);
    }
    else
        echo json_encode(['error' => "Invalid request"]);
?>

