<?php
    include "templates/functions.php";
?>

<!DOCTYPE html>
<html>
    <?php
        $page_title = "Register";
        include "templates/head.php";
    ?>
    
    <body class="body">
        <!-- Header -->
        <?php include "templates/header.php"; ?>
        
        <!-- Content -->
        <div class="content">
            <div class="content-header">
                <h2>Please enter your details:</h2>
            </div>
            
            <div class="form-container">
                <form id="register-form" action="<?php echo $base_url ?>/actions/registration.php" method="POST">
                    <label id="register-name" class="label">Name:</label>
                    <input id="name-input" class="input" type="text" name="name" tabindex="1" required/>
                    
                    <label id="register-surname" class="label">Surname:</label>
                    <input id="surname-input" class="input" type="text" name="surname" tabindex="2" required/>
                    
                    <label id="register-username" class="label">Username:</label>
                    <input id="username-input" class="input" type="text" name="username" tabindex="3" required/>
                    
                    <!-- TODO: Add confirm password field later -->
                    
                    <label id="register-password" class="label">Password:</label>
                    <label id="register-password" class="label">Password:</label>
                    <input id="password-input" class="input" type="password" name="password" minlength="6" tabindex="4" required />
                   
                    <input id="register-submit" class="form-button" type="submit" value="Register" />
                    <input id="register-cancel" class="form-button" type="button" value="Cancel" />
                    <?php
                        if (isset($_SESSION['login-failed']))
                        {
                            echo "<div>Login invalid!</div>";
                            unset($_SESSION['login-failed']);
                        }
                    ?>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <?php include "templates/footer.php"; ?>
    </body>
</html>
