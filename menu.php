<?php
    include "templates/functions.php";
?>

<!DOCTYPE html>
<html>
    <?php
        $page_title = "Menu";
        include "templates/head.php";
    ?>
    
    <body class="body">
        <!-- Header -->
        <?php include "templates/header.php"; ?>
        
        <!-- Content -->
        <div class="content">
            <section class="main-menu">
                <section id="menu-panel">
                    <div class="content-header">
                        <h2>Welcome!</h2>
                    </div>
                    <input id="create-game" class="menu-button" type="button" value="Create Game" />
                    <input id="leaderboard" class="menu-button" type="button" value="Leaderboard" />
                    <input id="my-scores" class="menu-button" type="button" value="My Scores" />
                    <input id="my-open-games" class="menu-button" type="button" value="My Open Games" />
                    <!--<input id="sign-out" class="menu-button" type="button" value="Sign Out" />-->
                </section>
                <section id="open-games" class="">
                    <div class="content-header">
                        <h2>Open Games</h2>
                    </div>
                    <div id="open-games-header">
                        <div>Host</div>
                        <div>Lobby Name</div>
                        <div>Date Created</div>
                    </div>
                    <div id="open-games-table">
                        
                    </div>
                </section>
            </section>
        </div>

        <!-- Footer -->
        <?php include "templates/footer.php"; ?>
        
        <script>
            <?php if (isset($_SESSION["ttt_player_id"])): ?>
                var _player_id = <?php echo htmlspecialchars($_SESSION["ttt_player_id"]); ?>;
            <?php endif; ?>
        </script>
    </body>
</html>
