<?php
    include "templates/functions.php";
?>

<!DOCTYPE html>
<html>
    <?php
        $page_title = "My Scores";
        include "templates/head.php";
    ?>
    
    <body class="body">
        <!-- Header -->
        <?php include "templates/header.php"; ?>
        
        <!-- Content -->
        <div class="content">
            <section class="my-game-grid">
                <div class="content-header">
                        <h2>My Games</h2>
                    </div>
                <div id="my-game-header">
                    <div>Game ID</div>
                    <div>Player 1</div>
                    <div>Player 2</div>
                    <div>Game Date</div>
                </div>
                <div id="my-game-rows">
                    <?php
                        // Load leaderboard data
                        $response = $client->showAllMyGames([
                            'uid' => $_SESSION['ttt_player_id']
                        ]);
                        switch($response->return)
                        {
                            case 'ERROR-NOGAMES':
                                echo "<div class=\"my-game odd\">No games found!</div>";
                                break;
                            case 'ERROR-DB':
                                echo "<div class=\"my-game odd\">Error connecting to database!</div>";
                                break;
                            default:
                                $odd = true;
                                foreach (explode("\n", $response->return) as $row)
                                {
                                    $row = explode(",", $row);
                                    if ($odd)   echo "<div class=\"my-game-row data-odd\">";
                                    else        echo "<div class=\"my-game-row data-even\">";
                                    $odd = !$odd; // Invert
                                    echo "<div class=\"my-game-row-item\">$row[0]</div>";
                                    echo "<div class=\"my-game-row-item\">$row[1]</div>";
                                    echo "<div class=\"my-game-row-item\">$row[2]</div>";
                                    echo "<div class=\"my-game-row-item\">"
                                        . date_format(date_create($row[3]), "dS F Y H:i:s") . "</div>";
                                    echo "</div>";
                                }
                                break;
                        }
                    ?>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <?php include "templates/footer.php"; ?>
    </body>
</html>
