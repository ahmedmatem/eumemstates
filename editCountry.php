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

    $countryId = 0;
    if(isset($_GET)){
        $countryId = $_GET['id'];
    }
    
    $countryData = new CountryData();
    $country = $countryData->getCountryById($countryId);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit country</title>
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
            
            <div id="main-content">
                <div class="row">
                    <div class="col-md-2"></div>
                    <h1 class="col-md-10">Edit country</h1>
                </div>
                
                <form action="editprocesse.php" method="post" id="edit-form" enctype="multipart/form-data" class="form-horizontal" role="form">
                    <input type="hidden" id="countryId" name="countryId" value="<?= $countryId?>" />
                    
                    <?php
                    if(isset($_SESSION['country-err']['name'])) {
                        echo '<div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-5 text-color-red">' . $_SESSION['country-err']['name'] . '</div>
                            </div>';
                    }
                    ?>
                    <div class="form-group">
                        <label for="inputCountryName" class="col-md-2 control-label">Country</label>
                        <div class="col-md-5">
                            <input type="text" value="<?= $country->name?>" class="form-control" id="inputCountryName" name="inputCountryName" />
                            <div class="text-color-red" id="err-message-country"></div>
                        </div>
                    </div>
                    
                    <?php
                    if(isset($_SESSION['country-err']['capital'])) {
                        echo '<div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-5 text-color-red">' . $_SESSION['country-err']['capital'] . '</div>
                            </div>';
                    }
                    ?>
                    <div class="form-group">
                        <label for="inputCapitalName" class="col-md-2 control-label">Capital</label>
                        <div class="col-md-5">
                            <input type="text" value="<?= $country->capital?>" class="form-control" id="inputCapitalName" name="inputCapitalName" />
                            <div class="text-color-red" id="err-message-capital"></div>
                        </div>
                    </div>
                    
                    <?php
                    if(isset($_SESSION['country-err']['population'])) {
                        echo '<div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-5 text-color-red">' . $_SESSION['country-err']['population'] . '</div>
                            </div>';
                    }
                    ?>
                    <div class="form-group">
                        <label for="inputPopulation" class="col-md-2 control-label">Population</label>
                        <div class="col-md-2">
                            <input type="text" value="<?= $country->population?>" class="form-control" id="inputPopulation" name="inputPopulation" />
                            <div class="text-color-red" id="err-message-population"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="state" class="col-md-2 control-label">State</label>
                        <div class="col-md-2">
                            <select name="state" id="state" class="form-control">
                                <option value="v" <?= $country->state == 'v' ? 'selected' : ''?>>visible</option>
                                <option value="h" <?= $country->state == 'h' ? 'selected' : ''?>>hidden</option>
                            </select>
                        </div>
                    </div>
                    
                    <?php
                    if(isset($_SESSION['fileUploadError'])) {
                        echo '<div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-5 text-color-red">' . $_SESSION['fileUploadError'] . '</div>
                            </div>';
                    }
                    ?>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Flag</label>
                        <div class="col-md-5">
                            <input type="file" class="form-control" id="flagFile" name="flagFile" />
                            <div class="text-color-red" id="err-message-flag"></div>
                        </div>
                        <img class="flag" alt="flag" src="images/euflags/<?= $country->flag?>" />
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                            <a href="manage.php" role="button" class="btn btn-success">Cancel</a>
                        </div>
                    </div>
                </form>
                
            </div>
            
            <footer>
                <!-- Footer goes here ... -->
            </footer>
            
            <script>
                // Country edit-form validation
                $('button[type="submit"]').click(function (e) {
                    e.preventDefault();
                    
                    var country_err = false;
                    var capital_err = false;
                    var population_err = false;
                    var flag_err = false;
                    
                    var alphabetRegx = /^[A-Za-z]+$/;
                    var digitsRegx = /^\d+$/;
                    
                    // validate country name: alphabet[3..50]
                    var countryName = $('#inputCountryName').val();
                    if(countryName.length < 3 || 50 < countryName.length) {
                        $('#err-message-country').html('Country name must be between 3 and 50 letters.').show();
                        country_err = true;
                    }
                    else {
                        if(!alphabetRegx.test(countryName)){
                            $('#err-message-country').html('Country name must contain only letters.').show();
                            country_err = true;
                        }
                    }
                    
                    // validate capital name: alphabet[3..50]
                    var capitalName = $('#inputCapitalName').val();
                    if(capitalName.length < 3 || 50 < capitalName.length) {
                        $('#err-message-capital').html('Capital name must be between 3 and 50 letters.').show();
                        capital_err = true;
                    }
                    else {
                        if(!alphabetRegx.test(capitalName)){
                            $('#err-message-capital').html('Capital name must contain only letters.').show();
                            capital_err = true;
                        }
                    }
                    
                    // validate population: number less than 300,000,000
                    var population = $('#inputPopulation').val();
                    population = population.replace(/\b,\b/gi, '');
                    if(!digitsRegx.test(population)){
                        $('#err-message-population').html('Population must bi integer.').show();
                        population_err = true;
                    }
                    else {
                        var populationAsInt = parseInt(population);
                        if(populationAsInt < 0 || 300000000 < populationAsInt){
                            $('#err-message-population').html('Population must bi integer less than 300,000,000.').show();
                            population_err = true;
                        }
                    }
                    
                    // validate input flag
                    var fileName = $('#flagFile').val();
                    var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
                    if(fileName != "" && fileExtension != 'jpeg' && fileExtension != 'jpg' && fileExtension != 'gif'){
                        $('#err-message-flag').html('File must be .gif or .jpg/jpeg.').show();
                        flag_err = true;
                    }
                    
                    
                    if(!country_err && !capital_err && !population_err && !flag_err){
                        // submit form
                        $('#edit-form').submit();
                    }
                    
                    if(!country_err){
                        $('#err-message-country').html('').hide();
                    }
                    
                    if(!capital_err){
                        $('#err-message-capital').html('').hide();
                    }
                    
                    if(!population_err){
                        $('#err-message-population').html('').hide();
                    }
                    
                    if(!flag_err){
                        $('#err-message-flag').html('').hide();
                    }
                });
            </script>
        </div>
    </body>
</html>

<?php
if(isset($_SESSION['country-err'])) {
    unset($_SESSION['country-err']);
}

if(isset($_SESSION['fileUploadError'])) {
    unset($_SESSION['fileUploadError']);
}
?>