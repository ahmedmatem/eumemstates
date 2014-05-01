<?php
session_start();
session_regenerate_id();
    
require_once 'models/Validator.php';
require_once 'models/Registry.php';
require_once 'models/DB.php';

if(!$_POST){
    header("Location: index.php");
    exit();
}

if(isset($_SESSION['err_messages'])) {
    unset($_SESSION['err_messages']);
}

$validator = new Validator();
// validate username field - alphanumeric, length beetween3 and 15
$username = trim($_POST['inputUsername']);
if(!$validator->inputFieldLength('Username', $username, 3, 15)) {
    $_SESSION['err_messages']['username'] = $validator->error['inputFieldLength'];
}

if(!$validator->alphanumeric('Username', $username)) {
    $_SESSION['err_messages']['username'] = $validator->error['alphanumeric'];
}

// validate password field - alphanumeric, length beetween3 and 15
$password = trim($_POST['inputPassword']);
if(!$validator->inputFieldLength('Password', $password, 3, 15)) {
    $_SESSION['err_messages']['password'] = $validator->error['inputFieldLength'];
}

if(!$validator->alphanumeric('Password', $password)) {
    $_SESSION['err_messages']['password'] = $validator->error['alphanumeric'];
}

if(!isset($_SESSION['err_messages'])) {
    // validation passed
    //check username and password in database
    $db = DB::getInstance();
    $con = $db->getConnection();
    
    $stmt = mysqli_prepare($con, "SELECT role FROM users WHERE Username=? AND Password=?");
    $stmt->bind_param("ss", $username, md5($password));
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();
    
    if(isset($role)){
        // the user exists
        if($role == 0) {    // 0 - administrator, 1 - default user
            $_SESSION['admin'] = true;
        }
        
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $username;
        
        header('Location: manage.php');
        exit();
    }
    else {
        // the user not exists
        if(isset($_SESSION['loggedIn'])) {
            unset($_SESSION['loggedIn']);
        }
        $_SESSION['err_messages']['user_not_exists'] = 'The user with specific name and password not exists.';
    }
    
}

// validation failed
header('Location: login.php');
exit();