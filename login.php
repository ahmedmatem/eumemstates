<?php
    session_start();
    session_regenerate_id();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/bootstrap-theme.min.css" rel="stylesheet" />
        <link href="css/font-awesome.min.css" rel="stylesheet" />
        <link href="css/site.css" rel="stylesheet" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <div id="containerId">
            <?php
                include 'includes/header.php';
                include 'includes/nav.php';
            ?>
            
            <br />
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <p class="bg-primary text-center loginTitle"><strong>Login</strong></p>
                    <?php
                        if(isset($_SESSION['err_messages']['user_not_exists']) ) {
                            echo '<div id="user-not-exists-err"  class="text-color-red text-center">' . $_SESSION['err_messages']['user_not_exists'] . '</div>';
                        }
                    ?>
                </div>
                <div class="col-md-4"></div>
            </div>  
            <div class="row">
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                    <form id="login-form" method="post" action="loginProcess.php">
                        <div class="form-group">
                            <label for="inputUsername">Username</label>
                            <input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="username">
                            <?php
                                if(isset($_SESSION['err_messages']['username']) ) {
                                    echo '<div class="text-color-red text-center">' . $_SESSION['err_messages']['username'] . '</div>';
                                }
                            ?>
                            <div class="text-color-red text-center" id="err-message-username"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="password">
                            <?php
                                if(isset($_SESSION['err_messages']['password']) ) {
                                    echo '<div class="text-color-red text-center">' . $_SESSION['err_messages']['password'] . '</div>';
                                }
                            ?>
                            <div class="text-color-red text-center" id="err-message-password"></div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" id="login-button" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4"></div>
            </div>
            
            <footer>
                <!-- Footer goes here ... -->
            </footer>
        </div>
        
        <script>
            // Login form validation
            $('#login-button').click(function (e) {
                e.preventDefault();
                
                var username_err = false;
                var password_err = false;
                var regx = /^[a-z0-9]+$/i;  // alphanumeric
                
                var username = $('#inputUsername').val();
                var password = $('#inputPassword').val();
                
                $('#user-not-exists-err').hide();
                
                // validate username
                if(username.length < 5 || 15 < username.length ){
                    $('#err-message-username').html('Username length must be between 5 and 15.').show();
                    username_err = true;
                }
                else {
                    if(!regx.test(username)){
                      $('#err-message-username').html('Username must contain letters and digits only.').show();
                      username_err = true;
                    }
                }
                
                // validate password
                if(password.length < 5 || 15 < password.length ){
                    $('#err-message-password').html('Password length must be between 5 and 15.').show();
                    password_err = true;
                }
                else {
                    if(!regx.test(password)){
                      $('#err-message-password').html('Password must contain letters and digits only.').show();
                      password_err = true;
                    }
                }
                
                if(!username_err && !password_err) {
                    // validation passed
                    $('#login-form').submit();
                }
                
                // validation failed
                
                if(!username_err){
                    $('#err-message-username').hide();
                }
                
                if(!password_err){
                    $('#err-message-password').hide();
                }
            });
        </script>
    </body>
</html>

<?php
if(isset($_SESSION['err_messages'])){
    unset($_SESSION['err_messages']);
}
