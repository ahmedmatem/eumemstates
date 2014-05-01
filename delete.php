<?php

session_start();
session_regenerate_id();
    
require_once 'models/Country.php';
require_once "models/DB.php";
require_once 'data/CountryData.php';

if(!isset($_SESSION['loggedIn'])) {
    header('Location: index.php');
    exit();
}

if(isset($_GET)) {
    $id = $_GET['id'];
    
    $country = (new CountryData())->getCountryById($id);
    // delete flag-file from server
    unlink('images/euflags/' . $country->flag);
    
    $db = DB::getInstance();
    $con = $db->getConnection();
    
    $stmt = mysqli_prepare($con, "DELETE FROM countries WHERE countryId=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: manage.php');
exit();

