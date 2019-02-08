<?php
    include "../templates/functions.php";

    if (isset($_POST['_player_id']) && isset($_POST['_game_id']))
    {
        $response = $client->joinGame([
            'gid' => htmlspecialchars($_POST['_game_id']),
            'uid' => htmlspecialchars($_POST['_player_id'])
        ]);
        /*
         * TODO: Change this to a POLL later that is submitted via an
         * event listener and display if user can join the lobby or not
         */
        switch ($response->return)
        {
            case 'ERROR-DB':
                $response = [
                    'error' => 'Error accessing the database!'
                ];
                break;
            case 0: // Unsuccesful joining game
                $response = [
                    'message' => 0
                ];
                break;
            case 1: // Successfully joined game
                $response = [
                    'message' => 1,
                    'game_id' => htmlspecialchars($_POST['_game_id'])
                ];
                break;
        }
        // Send back JSON response
        echo json_encode($response);
    }
    else
        echo json_encode(['error' => "Invalid request"]);
?>
