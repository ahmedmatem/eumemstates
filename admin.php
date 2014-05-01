<?php

session_start();
session_regenerate_id();

if(!isset($_SESSION['admin'])){
    header('Location: login.php');
    exit();
}



