<?php
    include "templates/functions.php";
?>

<!DOCTYPE html>
<html>
    <?php
        $page_title = "Open Games";
        include "templates/head.php";
    ?>
    
    <body class="body">
        <!-- Header -->
        <?php include "templates/header.php"; ?>
        
        <!-- Content -->
        <div class="content">
            <section class="open-game-grid">
                <div class="content-header">
                        <h2>My Open Games</h2>
                    </div>
                <div id="open-game-header">
                    <div>Game ID</div>
                    <div>Host</div>
                    <div>Game Date</div>
                </div>
                <div id="open-game-rows">
                    <?php
                        // Load leaderboard data
                        $response = $client->showMyOpenGames([
                            'uid' => $_SESSION['ttt_player_id'] 
                        ]);
                        switch($response->return)
                        {
                            // g.autokey, u.username, g.started}\n 
                            case 'ERROR-NOGAMES':
                                echo "<div class=\"leaderboard-row odd\">No games found!</div>";
                                break;
                            case 'ERROR-DB':
                                echo "<div class=\"leaderboard-row odd\">Error connecting to database!</div>";
                                break;
                            default:
                                $odd = true;
                                foreach (explode("\n", $response->return) as $row)
                                {
                                    echo "<div class=\"data-row ";
                                    if ($odd)
                                            echo "data-odd";
                                        else
                                            echo "data-even";
                                        $odd = !$odd; // Invert
                                    echo "\">";
                                    $i = 0;
                                    $items = explode(",", $row);
                                    foreach ($items as $item)
                                    {
                                        echo "<div class=\"open-game-row-item";
                                        if ($i == 2)
                                            $item = date_format(date_create($item), "dS F Y H:i:s");
                                        echo "\">$item</div>";
                                        $i++;
                                    }
                                    
                                    // Add delete button
                                    echo "<div class=\"open-game-row-item\">";
                                    echo "<form method=\"POST\" action=\"$base_url/actions/deletegame.php\">";
                                    echo "<input name=\"ttt_game_id\" type=\"hidden\" value=\"" . $items[0] . "\" />";
                                    echo "<input name=\"ttt_player_id\" type=\"hidden\" value=\"" . $_SESSION['ttt_player_id'] ."\" />";
                                    echo "<input type=\"submit\" class=\"open-game-row-item\" value=\"Delete\" />";
                                    echo "</form>";
                                    echo "</div>";
                                    
                                    // Add join button
                                    echo "<div class=\"open-game-row-item\">";
                                    echo "<form method=\"POST\" action=\"$base_url/game.php\">";
                                    echo "<input name=\"ttt_game_id\" type=\"hidden\" value=\"" . $items[0] . "\" />";
                                    echo "<input name=\"ttt_player_id\" type=\"hidden\" value=\"" . $_SESSION['ttt_player_id'] ."\" />";
                                    echo "<input type=\"submit\" class=\"open-game-row-item\" value=\"Open\" />";
                                    echo "</form>";
                                    echo "</div>";
                                    
                                    // End data-row
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
