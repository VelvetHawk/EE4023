<?php
    include "../templates/functions.php";
    
    if (!isset($_POST['username']) || !isset($_POST['password'])
        || $_POST['username'] == "" || $_POST['password'] == "")
    {
        $_SESSION['login-missing'] = true;
        header("Location: $base_url/index.php"); // Redirect to home
        die();
    }
    
    // Information present, continue
    
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    
    $response = $client->login(array(
        'username' => $username, 'password' => $password
    ));
    
    /*
     * Returns > 0 (user's id) if successful
     */
    if ($response->return > 0)
    {
        $_SESSION['ttt_username'] = $username;
        $_SESSION['ttt_player_id'] = $response->return;
        header("Location: $base_url/menu.php"); // Redirect to home
        die();
    }
    else
    {
        $_SESSION['login-failed'] = true;
        header("Location: $base_url/index.php"); // Redirect to home
        die();
    }
?>