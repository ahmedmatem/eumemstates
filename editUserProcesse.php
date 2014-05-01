<?php

session_start();
session_regenerate_id();

require_once "models/DB.php";

if(!isset($_SESSION['admin'])){
    header('Location: index.php');
    exit();
}

if(isset($_SESSION['edit_user_exist'])) {
    unset($_SESSION['edit_user_exist']);
}

$userId = $_POST['userId'];
$username = $_POST['inputUsername'];
$password = $_POST['inputPassword'];
$role = $_POST['role'];



$db = DB::getInstance();
$con = $db->getConnection();

$result = $con->query('SELECT * FROM users WHERE username="' . $username . '" AND userId!=' . $userId);
$user_exist = false;
while($row = mysqli_fetch_assoc($result)){
    $user_exist = true;
    break;
}

if($user_exist) {
    $_SESSION['edit_user_exist'] = 'User with name "' . $username . '" already exist.';
    header('Location: edituser.php?id=' .$userId);
    exit();
}

if($password != ''){    // change password
    $stmt = mysqli_prepare($con, 'UPDATE users SET username=?, password=?, role=? WHERE userId="' . $userId .'"');
    $stmt->bind_param('ssi', $username, md5($password), $role);
}
else {  // not new password
    $stmt = mysqli_prepare($con, 'UPDATE users SET username=?, role=? WHERE userId="' . $userId .'"');
    $stmt->bind_param('si', $username, $role);
}

$stmt->execute();

header('Location: users.php');
exit();

