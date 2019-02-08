<?php
    include "templates/functions.php";
?>

<!DOCTYPE html>
<html>
    <?php
        $page_title = "Leaderboard";
        include "templates/head.php";
    ?>
    
    <body class="body">
        <!-- Header -->
        <?php include "templates/header.php"; ?>
        
        <!-- Content -->
        <div class="content">
            <section class="leaderboard-grid">
                <div class="content-header">
                        <h2>Leaderboard</h2>
                    </div>
                <div id="leaderboard-header">
                    <div>Game ID</div>
                    <div>Player 1</div>
                    <div>Player 2</div>
                    <div>Game Status</div>
                    <div>Game Date</div>
                </div>
                <div id="leaderboard-rows">
                    <?php
                        // Load leaderboard data
                        $response = $client->leagueTable();
                        switch($response->return)
                        {
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
                                    echo "<div class=\"leaderboard-row ";
                                    if ($odd)
                                            echo "data-odd";
                                        else
                                            echo "data-even";
                                        $odd = !$odd; // Invert
                                    echo "\">";
                                    $i = 0;
                                    foreach (explode(",", $row) as $item)
                                    {
                                        echo "<div class=\"leaderboard-row-item";
                                        if ($i == 3)
                                            switch ($item)
                                            {
                                                case -1: $item = "Not Started"; break;
                                                case 0: $item = "Ongoing"; break;
                                                case 1: $item = "Player 1 won"; break;
                                                case 2: $item = "Player 2 won"; break;
                                                case 3: $item = "Draw"; break;
                                            }
                                        else if ($i == 4)
                                            $item = date_format(date_create($item), "dS F Y H:i:s");
                                        echo "\">$item</div>";
                                        $i++;
                                    }
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
