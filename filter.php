<?php
session_start();
session_regenerate_id();

$_SESSION['filter'] = 0;    // filter: none;
$_SESSION['filterResult'] = '';

if(isset($_POST)) {
    $filter = $_POST['filter'];
    $inputKeyword = trim($_POST['inputKeyword']);
    
    $_SESSION['keyword'] = $inputKeyword;
    
    switch ($filter) {
        case 1: // whole word
            $_SESSION['filter'] = 1;    // filter: whole word only;
            break;
        case 2:
            $_SESSION['filter'] = 2;    // filter: starts with;
            break;
        case 3:
            $_SESSION['filter'] = 3;    // filter: ends with;
            break;
        case 4:
            $_SESSION['filter'] = 4;    // filter: contains;
            break;
        default:
            $_SESSION['filter'] = 0;    // filter: none;
            break;
    }
}

header('Location: manage.php');
exit();

