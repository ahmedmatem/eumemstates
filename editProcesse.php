<?php

session_start();
session_regenerate_id();

if(!isset($_SESSION['loggedIn'])) {
    header('Location: index.php');
    exit();
}

require_once 'models/Country.php';
require_once "models/DB.php";
require_once 'data/CountryData.php';

$db = DB::getInstance();
$con = $db->getConnection();

if(isset($_SESSION['country-err'])) {
    unset($_SESSION['country-err']);
}

if(isset($_SESSION['fileUploadError'])) {
    unset($_SESSION['fileUploadError']);
}

$countryId = trim($_POST['countryId']);
$country = trim($_POST['inputCountryName']);
$capital = trim($_POST['inputCapitalName']);
$population = str_replace(',', '', trim($_POST['inputPopulation']));
$state = trim($_POST['state']);

// VALIDATE INPUT FIELDS:

// alphabet validation for country
if(!preg_match('/^[a-zA-Z]+$/', $country)){
    $_SESSION['country-err']['name'] = 'Country name must contain only letters.';
}

// available country name length validation
if(strlen($country) < 3 || 50 < strlen($country)) {
    $_SESSION['country-err']['name'] = 'Country name length must be between 3 and 50 letters.';
}

// Check if country already exist in DB
$query = "SELECT * FROM countries WHERE name='" . mysqli_real_escape_string($con, $country) . "' AND countryId!='" . $countryId . "'";
$result = $con->query($query);
while($row = mysqli_fetch_assoc($result)) {
    $_SESSION['country-err']['name'] = "Country " . $country . " already exists.";
    break;
}

// alphabet validation for capital
if(!preg_match('/^[a-zA-Z]+$/', $capital)){
    $_SESSION['country-err']['capital'] = 'Capital name must contain only letters.';
}

// available capital name length validation
if(strlen($capital) < 3 || 50 < strlen($capital)) {
    $_SESSION['country-err']['capital'] = 'Capital name length must be between 3 and 50 letters.';
}

// population value validation: must be less than 300,000,000
$population = str_replace(',', '', $population);
if(!preg_match('/^\d+$/', $population)) {
    $_SESSION['country-err']['population'] = 'Population must be integer less than 300,000,000.';
}
else if($population < 0 || 300000000 < $population) {
    $_SESSION['country-err']['population'] = 'Population must be less than 300,000,000.';
}

if(isset($_SESSION['country-err'])){
    // invalide country data
    header('Location: editCountry.php?id=' . $countryId);
    exit();
}

$countryFromDB = (new CountryData())->getCountryById($countryId);
$filename = $countryFromDB->flag;

// restrict uploaded file:
// file type: only gif, jpeg, jpg.
// file size: up to 100KB
$allowedExts = array("gif", "jpeg", "jpg");
$temp = explode(".", $_FILES["flagFile"]["name"]);
$extension = end($temp);

if($_FILES['flagFile']['error'] > 0){
    // no file
}
else {
    // processe file uploading
    
    if((($_FILES['flagFile']['type'] == 'image/gif')
            || ($_FILES['flagFile']['type'] == 'image/jpeg')
            || ($_FILES['flagFile']['type'] == 'image/jpg'))
            && ($_FILES['flagFile']['size'] < 100000)
            && (in_array($extension, $allowedExts))) {
        
        // delete old flag
        unlink('images/euflags/' . $countryFromDB->flag);        
        // generate new flag name and save it
        $flagName = explode('.',$countryFromDB->flag);
        $filename = $flagName[0] . '.' . $extension;
        move_uploaded_file($_FILES['flagFile']['tmp_name'], 'images/euflags/' . $filename);
    }
    else {
        $_SESSION['fileUploadError'] = 'Error: Uploaded file must be of type .jpg or .gif and its size must be less than 100KB.';
        header('Location: editCountry.php?id=' . $countryId);
        exit();
    }
    
}

$stmt = mysqli_prepare($con, "UPDATE countries SET name=?, capital=?, population=?, state=?, flag=? WHERE countryId=" . $countryId);
$stmt->bind_param("ssiss", $country, $capital, $population, $state, $filename);
$stmt->execute();

header('Location: manage.php?');
exit();
