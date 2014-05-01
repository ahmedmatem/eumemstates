<?php

session_start();
session_regenerate_id();

if(isset($_SESSION['loggedIn'])){
    unset($_SESSION['loggedIn']);
}

if(isset($_SESSION['admin'])){
    unset($_SESSION['admin']);
}

if(isset($_SESSION['username'])){
    unset($_SESSION['username']);
}

header("Location: index.php");
exit();