<?php

class CountryData{
    private $_allCountries = array();
    private  $_sortBy = 0;   // default
    
    public function getCountries($sortBy, $filter = 0, $keyword = ''){
        $db = DB::getInstance();
        $con = $db->getConnection();
        $query = "SELECT * FROM countries";
        
        $keyword = mysqli_real_escape_string($con, $keyword);
        switch ($filter) {
            case 1: // filter: whole word;
                $query .= ' WHERE capital="' . $keyword . '"';
                break;
           case 2:  // filter: starts with;
                $query .= ' WHERE capital LIKE "' . $keyword . '%"';
                break;
            case 3: // filter: ends with;
                $query .= ' WHERE capital LIKE "%' . $keyword . '"';
                break;
            case 4: // filter: contains;
                $query .= ' WHERE capital LIKE "%' . $keyword . '%"';
                break;
            default:
                break;
        }
        
        switch ($sortBy){
            case 0: // sort by name / asc
                $query .= ' ORDER BY name ASC';
                break;
            case 1: // sort by name / desc
                $query .= ' ORDER BY name DESC';
                break;
            case 2: // sort by population / asc
                $query .= ' ORDER BY population ASC';
                break;
            case 3: // sort by population / desc
                $query .= ' ORDER BY population DESC';
                break;
            default :
                break;
        }
        
        $result = $con->query($query);
        while($row = mysqli_fetch_assoc($result)){
            $country = new Country();
            $country->id = $row['CountryId'];
            $country->name = $row['Name'];
            $country->capital = $row['Capital'];
            $country->population = $row['Population'];
            $country->state = $row['State'];
            $country->flag = $row['Flag'];
            
            $this->_allCountries[] = $country;
        }
        
        return $this->_allCountries;
    }
    
    public function getVisibleCountries($sortBy, $filter = 0, $keyword = ''){
        $db = DB::getInstance();
        $con = $db->getConnection();
        $query = "SELECT * FROM countries WHERE state='v'"; // v - visible
        
        $keyword = mysqli_real_escape_string($con, $keyword);
        switch ($filter) {
            case 1: // filter: whole word;
                $query .= ' WHERE capital="' . $keyword . '"';
                break;
           case 2:  // filter: starts with;
                $query .= ' WHERE capital LIKE "' . $keyword . '%"';
                break;
            case 3: // filter: ends with;
                $query .= ' WHERE capital LIKE "%' . $keyword . '"';
                break;
            case 4: // filter: contains;
                $query .= ' WHERE capital LIKE "%' . $keyword . '%"';
                break;
            default:
                break;
        }
        
        switch ($sortBy){
            case 0: // sort by name / asc
                $query .= ' ORDER BY name ASC';
                break;
            case 1: // sort by name / desc
                $query .= ' ORDER BY name DESC';
                break;
            case 2: // sort by population / asc
                $query .= ' ORDER BY population ASC';
                break;
            case 3: // sort by population / desc
                $query .= ' ORDER BY population DESC';
                break;
            default :
                break;
        }
        
        $result = $con->query($query);
        while($row = mysqli_fetch_assoc($result)){
            $country = new Country();
            $country->id = $row['CountryId'];
            $country->name = $row['Name'];
            $country->capital = $row['Capital'];
            $country->population = $row['Population'];
            $country->state = $row['State'];
            $country->flag = $row['Flag'];
            
            $this->_allCountries[] = $country;
        }
        
        return $this->_allCountries;
    }
    
    public function getCountryById($id) {
        $db = DB::getInstance();
        $con = $db->getConnection();
        $query = "SELECT * FROM countries WHERE countryId='" . $id . "'";
        
        $result = $con->query($query);
        $country = new Country();
        while($row = mysqli_fetch_assoc($result)){
            $country->id = $row['CountryId'];
            $country->name = $row['Name'];
            $country->capital = $row['Capital'];
            $country->population = $row['Population'];
            $country->state = $row['State'];
            $country->flag = $row['Flag'];
            break;
        }
        
        return $country;
    }
    
    public function getSortBy(){
        return $this->_sortBy;
    }
}

