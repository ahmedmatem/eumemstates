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
    
    if(!isset($_SESSION['capital-length'])) {
        $_SESSION['capital-length'] = Country::$formatedCapitalMaxLength;
    }

    $countryData = new CountryData();
    $sortBy = 0;
    if(isset($_GET['sortBy'])){
        $sortBy = $_GET['sortBy'];
    }
    
    if(!isset($_SESSION['filter'])) {
        $_SESSION['filter'] = 0;    // filter: none;
        $_SESSION['keyword'] = '';
    }
    
    $countries = $countryData->getCountries($sortBy, $_SESSION['filter'], $_SESSION['keyword']);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>EU Manage</title>
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
            
            <div class="alert alert-success">Manage</div>
            
            <div id="main-content">
                <h1>List of member states of the European Union</h1>
                
                <div class="alert alert-info">
                    <form action="filter.php" id="filter-form" method="post" class="form-inline" role="form">
                        <div class="form-group">
                            <i class="fa fa-filter"></i> <strong>Filter</strong> by capital
                            <select class="form-control" id="filter"  name="filter">
                                <option value="0">Select capital filtering</option>
                                <option value="1" <?= $_SESSION['filter'] == 1 ? 'selected' : ''?>>whole word only</option>
                                <option value="2" <?= $_SESSION['filter'] == 2 ? 'selected' : ''?>>starts with</option>
                                <option value="3" <?= $_SESSION['filter'] == 3 ? 'selected' : ''?>>ends with</option>
                                <option value="4" <?= $_SESSION['filter'] == 4 ? 'selected' : ''?>>contains</option>
                            </select>
                            <?php
                            if($_SESSION['filter'] != 0) {
                                echo '<input type="text" class="form-control" name="inputKeyword" id="inputKeyword" value=' . $_SESSION['keyword'] . '>';
                            }
                            else{
                                echo '<input type="text" class="form-control" name="inputKeyword" id="inputKeyword" placeholder="word">';
                            }
                            ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <button type="submit" id="showAllButton" class="btn btn-success pull-right">Show all</button>
                    </form>
                </div>
                
                <?php
                if($_SESSION['filter'] != 0) {
                    $resultCount = count($countries);
                    $plural = $resultCount == 1 ? 'result ' : 'results ';
                    echo '<div><strong>' . $resultCount . ' ' . $plural . 'found.</strong></div>';
                }
                ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <a href="addcountry.php" title="Add new country"><i class="fa fa-plus"></i></a>&nbsp;
                                        County 
                                            <?php
                                            if($sortBy == 1){
                                            ?>
                                                <a href="manage.php?sortBy=0">
                                                    <i class="fa fa-caret-down"></i></a>
                                            <?php
                                            }
                                            else {
                                            ?>
                                                <a href="manage.php?sortBy=1">
                                                <i class="fa fa-caret-up"></i></a>
                                            <?php
                                            }
                                            ?>
                                    </th>
                                    <th class="text-center">
                                        Capital 
                                        <select title="Select capital length" id="capital-length">
                                            <?php
                                            for($i = Country::$formatedCapitalMinLength; $i <= Country::$formatedCapitalMaxLength; $i++) {
                                                if($i == $_SESSION['capital-length']){
                                                    echo '<option selected value="' . $i . '">' . $i . '</option>';
                                                }
                                                else {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        Population 
                                        <?php
                                            if($sortBy == 3){
                                            ?>
                                                <a href="manage.php?sortBy=2">
                                                <i class="fa fa-caret-down"></i></a>
                                            <?php
                                            }
                                            else {
                                            ?>
                                                <a href="manage.php?sortBy=3">
                                                <i class="fa fa-caret-up"></i></a>
                                            <?php
                                            }
                                            ?>
                                    </th>
                                    <th class="text-center">Flag</th>
                                    <th class="text-center">State</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($countries as $country) {
                                    if($country->getStateAsString() == 'hidden') {
                                        echo '<tr class="hidden-row">';
                                    }
                                    else {
                                        echo '<tr>';
                                    }
                                    echo '<td id="country-name-' . $country->id . '" class="col-md-3">' . $country->name . '</td>';
                                    echo '<td class="text-center col-md-2">' . $country->getFormatedCapital($_SESSION['capital-length']) . '</td>';
                                    echo '<td class="text-center col-md-2">' . $country->population . '</td>';
                                    echo '<td class="text-center col-md-1"><img class="flag" src="images/euflags/' . $country->flag . '" alt="flag" /></td>';
                                    echo '<td class="text-center col-md-1">' . $country->getStateAsString() . '</td>';
                                    echo '<td class="text-center col-md-1">'
                                        . '<a href="editcountry.php?id=' . $country->id . '" title="edit" ><i class="fa fa-edit"></i></a>&nbsp;&nbsp;'
                                        . '<a href="delete.php?id=' . $country->id . '" onclick="return confirm(\'Are you sure you want to delete country ' . $country->name . '?\')" title="delete"><i class="fa fa-trash-o"></i></button>'
                                        . '</td>';
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
            
            <script>
                $('#showAllButton').click(function (e){
                    e.preventDefault();
                    
                    $('#filter').prop('selectedIndex', 0);
                    $('#inputKeyword').prop('placeholder', 'word');
                    $('#inputKeyword').val('');
                    
                    $('#filter-form').submit();
                });
                
                $('#capital-length').change(function () {
                    window.location = 'resizecapital.php?size=' + $('#capital-length option:selected').val();
                });
            </script>
        </div>
        
        <script>
            $('button[title="edit"]').click(function (){
                var countryId = $(this).attr("id");
                
                $('.modal-header .modal-title').html($('#country-name-' + countryId).html());
            });
        </script>
    </body>
</html>
