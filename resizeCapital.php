<?php

session_start();
session_regenerate_id();

if(!isset($_SESSION['loggedIn'])) {
    header('Location: manage.php');
    exit();
}

if(isset($_GET)) {
    $_SESSION['capital-length'] = $_GET['size'];
}

header('Location: manage.php');
exit();



