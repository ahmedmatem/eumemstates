<?php
    session_start();
    session_regenerate_id();

    require_once 'models/Country.php';
    require_once "models/DB.php";
    require_once 'data/CountryData.php';

    $countryData = new CountryData();
    $sortBy = 0;
    if(isset($_GET['sortBy'])){
        $sortBy = $_GET['sortBy'];
    }
    
    $countries = $countryData->getVisibleCountries($sortBy);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>EU Member States</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/bootstrap-theme.min.css" rel="stylesheet" />
        <link href="css/font-awesome.min.css" rel="stylesheet" />
        <link href="css/site.css" rel="stylesheet" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <div id="containerId">
            <?php
                include 'includes/header.php';
                include 'includes/nav.php';
            ?>
            
            <?php
            if(isset($_SESSION['loggedIn'])) {
                echo '<div class="alert alert-success">Countries</div>';
            }
            ?>
            
            <div id="main-content">
                <h1>List of member states of the European Union</h1>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        County 
                                            <?php
                                            if($sortBy == 1){
                                            ?>
                                                <a href="index.php?sortBy=0">
                                                    <i class="fa fa-caret-down"></i></a>
                                            <?php
                                            }
                                            else {
                                            ?>
                                                <a href="index.php?sortBy=1">
                                                <i class="fa fa-caret-up"></i></a>
                                            <?php
                                            }
                                            ?>
                                    </th>
                                    <th class="text-center">Capital</th>
                                    <th class="text-center">
                                        Population 
                                        <?php
                                            if($sortBy == 3){
                                            ?>
                                                <a href="index.php?sortBy=2">
                                                <i class="fa fa-caret-down"></i></a>
                                            <?php
                                            }
                                            else {
                                            ?>
                                                <a href="index.php?sortBy=3">
                                                <i class="fa fa-caret-up"></i></a>
                                            <?php
                                            }
                                            ?>
                                    </th>
                                    <th class="text-center">Flag</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($countries as $country) {
                                    echo '<tr>';
                                    echo '<td class="col-md-6">' . $country->name . '</td>';
                                    if(isset($_SESSION['capital-length']) && $_SESSION['capital-length'] != Country::$formatedCapitalMaxLength){
                                        echo '<td class="text-center col-md-2">' . $country->getFormatedCapital($_SESSION['capital-length']) . '</td>';
                                    }
                                    else {
                                        echo '<td class="text-center col-md-2">' . substr($country->capital, 0, 1) . '</td>';
                                    }
                                    echo '<td class="text-center col-md-2">' . $country->population . '</td>';
                                    echo '<td class="text-center col-md-2"><img class="flag" src="images/euflags/' . $country->flag . '" alt="flag" /></td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <footer>
                <!-- Footer goes here ... -->
            </footer>
        </div>
    </body>
</html>
