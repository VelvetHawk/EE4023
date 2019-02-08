<?php
    include "../templates/functions.php";

    if (isset($_POST['ttt_player_id']) && isset($_POST['ttt_game_id']))
    {
        $response = $client->deleteGame([
            'gid' => htmlspecialchars($_POST['ttt_game_id']),
            'uid' => htmlspecialchars($_POST['ttt_player_id'])
        ]);
        
        switch ($response->return)
        {
            case 'ERROR-GAMESTARTED':
                $response = [
                    'error' => 'Game has already started!'
                ];
                break;
            case 'ERROR-DB':
                $response = [
                    'error' => 'Error accessing database!'
                ];
                break;
            default:
                // Re-direct, game deleted successfully
                header("Location: $base_url/open-games.php"); // Redirect to home
                echo json_encode($response);
                die();
                break;
        }
        
        // Send back JSON response
        echo json_encode($response);
    }
    else
        echo json_encode(['error' => "Invalid request"]);
?>
