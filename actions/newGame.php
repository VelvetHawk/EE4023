<?php
    include "../templates/functions.php";

    if (isset($_POST['player_id']))
    {
        $response = $client->newGame(['uid' => htmlspecialchars($_POST['player_id'])]);
        
        switch ($response->return)
        {
            case 'ERROR-NOTFOUND':
                $response = array('message' => 'Error occured: Game could not be found!');
                break;
            case 'ERROR-RETRIEVE':
                $response = array('message' => 'Error occured: Game data could not be retrieved!');
                break;
            case 'ERROR-INSERT':
                $response = array('message' => 'Error occured: Game could not be created!');
                break;
            case 'ERROR-DB':
                $response = array('message' => 'Error occured: Could not access database!');
                break;
            default:
                $response = [
                    'game_id' => $response->return
                ];
                break;
        }
        
        // Send back JSON response
        echo json_encode($response);
    }
    else
        echo json_encode(['error' => "Invalid request"]);
?>
