<?php
    include "templates/functions.php";
?>

<!DOCTYPE html>
<html>
    <?php
        $page_title = "Login";
        include "templates/head.php";
    ?>
    
    <body class="body">
        <!-- Header -->
        <?php include "templates/header.php"; ?>
        
        <!-- Content -->
        <div class="content">
            <section class="login">
                <div class="content-header">
                    <h2>Login</h2>
                </div>
                <div class="form-container">
                    <form id="login-form" action="<?php echo $base_url?>/actions/login.php" method="POST">
                        <label id="login-username" class="label">Username:</label>
                        <input id="username-input" class="input" type="text" name="username" tabindex="1" required />

                        <label id="login-password" class="label">Password:</label>
                        <input id="password-input" class="input" type="password" name="password" minlength="6" tabindex="2" required />

                        <input id="login-submit" class="form-button" type="submit" value="Login" />
                        <input id="login-sign-up" class="form-button" type="button" value="Sign Up" />
                        <?php
                            if (isset($_SESSION['login-failed']))
                            {
                                echo "<div>Login invalid!</div>";
                                unset($_SESSION['login-failed']);
                            }
                            if (isset($_SESSION['login-missing']))
                            {
                                echo "<div>Please fill out all of the fields!</div>";
                                unset($_SESSION['login-missing']);
                            }
                        ?>
                    </form>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <?php include "templates/footer.php"; ?>
    </body>
</html>
