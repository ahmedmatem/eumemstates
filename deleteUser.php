<?php

session_start();
session_regenerate_id();

if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}

$userId = $_GET['id'];

require_once 'models/DB.php';

$db = DB::getInstance();
$con = $db->getConnection();

$query = 'DELETE FROM users WHERE userId="' . $userId . '"';
$con->query($query);

header('Location: users.php');
exit();

