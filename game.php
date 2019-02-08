<?php
    include "templates/functions.php";
?>

<!DOCTYPE html>
<html>
    <?php
        $page_title = "Tic Tac Toe!";
        include "templates/head.php";
    ?>
    
    <body class="body">
        <!-- Header -->
        <?php include "templates/header.php"; ?>
        
        <!-- Content -->
        <div class="content">
            <section class="game">
                <section id="game-grid">
                    <div x="0" y="0" class="game-grid-cell odd"></div>
                    <div x="1" y="0" class="game-grid-cell even"></div>
                    <div x="2" y="0" class="game-grid-cell odd"></div>
                    <div x="0" y="1" class="game-grid-cell even"></div>
                    <div x="1" y="1" class="game-grid-cell odd"></div>
                    <div x="2" y="1" class="game-grid-cell even"></div>
                    <div x="0" y="2" class="game-grid-cell odd"></div>
                    <div x="1" y="2" class="game-grid-cell even"></div>
                    <div x="2" y="2" class="game-grid-cell odd"></div>
                </section>
                <section id="game-info">
                    <div id="game-status">Status:</div>
                    <div id="game-status-message"></div>
                    <div class="loader"></div>
                </section>
            </section>
        </div>

        <!-- Footer -->
        <?php include "templates/footer.php"; ?>
        
        <script>
            <?php if (isset($_POST["ttt_player_id"])): ?>
                var _player_id = <?php echo htmlspecialchars($_POST["ttt_player_id"]); ?>;
            <?php endif;
                if (isset($_POST["ttt_game_id"])): ?>
                    var _game_id = <?php echo htmlspecialchars($_POST["ttt_game_id"]); ?>;
                <?php endif; ?>
        </script>
        
    </body>
</html>
