<?php

session_start();
session_regenerate_id();

if(!isset($_SESSION['admin'])){
    header('Location: index.php');
    exit();
}

require_once 'models/User.php';
require_once "models/DB.php";
require_once 'data/UserData.php';

$users = (new UserData())->getAllUsers();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Users</title>
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
            
            <div class="alert alert-success">Users</div>
            
            <div id="main-content">                
                <div class="row">
                    <div class="col-md-6"><h2>List of all users</h2></div>
                    <div class="col-md-6"><h2 class="pull-right">Add new user</h2></div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($users as $user) {
                                    echo '<tr>
                                    <td>' . $user->username . '</td>
                                    <td class="text-center">' . $user->getRoleAsString() . '</td>
                                    <td class="text-center">
                                        <a href="edituser.php?id=' . $user->userId . '" title="edit"><i class="fa fa-edit"></i></a>&nbsp;
                                        <a href="deleteuser.php?id=' . $user->userId . '" onclick="return confirm(\'Are you sure you want to delete user: ' . $user->username . '\')" title="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <form action="adduser.php" method="post" id="add-form" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label for="inputUsername" class="col-sm-3 control-label">Username</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="username">
                                    <span class="text-color-red" id="err-message-username"></span>
                                    <?php
                                    if(isset($_SESSION['user_exist'])){
                                        echo '<span id="user-exist-err" class="text-color-red">' . $_SESSION['user_exist'] . '</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="inputPassword" class="col-sm-3 control-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Password">
                                    <span class="text-color-red" id="err-message-password"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="inputPasswordConfirm" class="col-sm-3 control-label">Confirm</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="inputPasswordConfirm" id="inputPasswordConfirm" placeholder="Confirm password">
                                    <span class="text-color-red" id="err-message-password-confirm"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="role" class="col-sm-3 control-label">Role</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="role" id="role">
                                        <option value="0">Administrator</option>
                                        <option value="1" selected>Default</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" id="add-button" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
                                    <button type="reset" id="reset-button" class="btn btn-success">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
            
            <footer>
                <!-- Footer goes here ... -->
            </footer>
        </div>
        
        <script>
            $('#add-button').click(function (e){
                e.preventDefault();
                
                var username_err = false;
                var password_err = false;
                var password_confirm_err = false;
                var regx = /^[a-z0-9]+$/i;  // alphanumeric
                
                var username = $('#inputUsername').val();
                var password = $('#inputPassword').val();
                var confirm = $('#inputPasswordConfirm').val();
                
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
                
                // validate password confirmation
                if(password != confirm){
                    $('#err-message-password-confirm').html('Password confirmation failed.').show();
                      password_confirm_err = true;
                }
                
                if(!username_err && !password_err && !password_confirm_err) {
                    // validation passed
                    $('#add-form').submit();
                }
                
                // validation failed
                
                if(!username_err){
                    $('#err-message-username').hide();
                }
                
                if(!password_err){
                    $('#err-message-password').hide();
                }
                
                if(!password_confirm_err){
                    $('#err-message-password-confirm').hide();
                }
                
            });
            
            $('#reset-button').click(function () {
                $('#err-message-username').hide();
                $('#err-message-password').hide();
                $('#err-message-password-confirm').hide();
                $('#user-exist-err').hide();
            });
        </script>
    </body>
</html>

<?php
if(isset($_SESSION['user_exist'])){
    unset($_SESSION['user_exist']);
}

