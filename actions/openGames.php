<?php
    include "../templates/functions.php";
    
    if (isset($_POST['_game_request']))
    {
        $response = $client->showOpenGames();
        // Error handling
        switch ($response->return)
        {
            case 'ERROR-NOGAMES':
                $response = [
                    'error' => "No open games found!"
                ];
                break;
            case 'ERROR-DB':
                $response = [
                    'error' => "Error accessing database!"
                ];
                break;
            default:
                $json_list = [];
                foreach (explode("\n", $response->return) as $row)
                {
                    $row = explode(",", $row);
                    array_push($json_list, [
                        'id' => $row[0],
                        'host' => $row[1],
                        'date-created' => $row[2]
                    ]);
                }
                $response = $json_list;
                break;
        }
        // Send back as JSON response
        echo json_encode($response);
    }
    else
        echo json_encode(['error' => "Invalid request"]);
    
?>

