<?php

session_start();
session_regenerate_id();

require_once "models/DB.php";

if(!isset($_SESSION['admin'])){
    header('Location: index.php');
    exit();
}

if(isset($_SESSION['user_exist'])) {
    unset($_SESSION['user_exist']);
}

$username = $_POST['inputUsername'];
$password = $_POST['inputPassword'];
$role = $_POST['role'];



$db = DB::getInstance();
$con = $db->getConnection();

$result = $con->query('SELECT * FROM users WHERE username="' . $username . '"');
$user_exist = false;
while($row = mysqli_fetch_assoc($result)){
    $user_exist = true;
    break;
}

if($user_exist) {
    $_SESSION['user_exist'] = 'User with name "' . $username . '" already exist.';
    header('Location: users.php');
    exit();
}

$stmt = mysqli_prepare($con, 'INSERT INTO users(username, password, role) VALUES(?, ?, ?)');
$stmt->bind_param('sss', $username, md5($password), $role);
$stmt->execute();

header('Location: users.php');
exit();

