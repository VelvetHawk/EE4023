<?php
    // Auto start the session
    session_start();
    
    $base_url = "/PHPClient";
    
    // Auto setup connection to WSDL
    $wsdl = "http://localhost:8080/TTTWebApplication/TTTWebService?WSDL";
    if (!isset($client))
    {
        try
        {
            $client = new SoapClient($wsdl, array('trace' => true, 'exceptions' => true));
        }
        catch (Exception $ex)
        {
            /*
         * Issue message to user that service is unavailable,
         * and relocate to index.php
         */
        }
    } 
    
    /*
     * THIS MUST ALWAYS BE LAST IN THIS FILE
     */
    // Check if user is logged in via username check
    $accessible_pages = array(
        "$base_url/index.php",
        "$base_url/register.php"
    );
    if (
        !strpos($_SERVER['REQUEST_URI'], "$base_url/actions/") == 0
        &&
        !in_array($_SERVER['REQUEST_URI'], $accessible_pages)
        &&
        (
            !isset($_SESSION['ttt_username'])
            ||
            empty($_SESSION['ttt_username'])
            ||
            !isset($_SESSION['ttt_user_id'])
            || 
            empty($_SESSION['ttt_user_id']))
        )
    {
        header("Location: " . $accessible_pages[0]); // Redirect to home/index.php
        die();
    }
    // Will either execute on the same page or redirected page
    unset($accessible_pages); // No longer necessary
?>
