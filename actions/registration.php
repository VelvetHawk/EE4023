<?php
    include "../templates/functions.php";
    
    if (!isset($_POST['name']) || !isset($_POST['surname'])
        || !isset($_POST['username']) || !isset($_POST['password']))
    {
        header("Location: $base_url/index.php"); // Redirect to home
        die();
    }
    
    // Information present, continue
    
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    
    $response = $client->register(array(
        'username' => $username,
        'password' => $password,
        'name' => $name,
        'surname' => $surname
    ));
    
    if ($response->return > 0) // Registration successful
    {
        // Redirect to home page and inform user
        header("Location: $base_url/index.php");
        die();
    }
    else // Registration unsuccessful
    {
        echo "What is this?: " . $response->return;
        
        switch ($response->return)
        {
            case 'ERROR-REPEAT':
                break;
            case 'ERROR-INSERT':
                break;
            case 'ERROR-RETRIEVE':
                break;
            case 'ERROR-DB':
                break;
        }
    }
?>

