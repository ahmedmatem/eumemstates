<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Registration</title>
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
            
            <div class="well">
            <form class="form-inline">
                <fieldset>
                    <legend>Quick registration form</legend>
                    <div class="form-group">
                        <label class="sr-only" for="inputUsername">Username</label>
                        <input type="text" class="form-control" id="inputUsername" placeholder="username">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="inputPassword">Password</label>
                        <input type="password" class="form-control" id="inputPassword" placeholder="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Sign in</button>
                </fieldset>
            </form>
            </div>
            
            <footer>
                <!-- Footer goes here ... -->
            </footer>
        </div>
    </body>
</html>