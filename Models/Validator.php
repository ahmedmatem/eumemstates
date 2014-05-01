<?php

class Validator {
    public $error = array();
    
    function inputFieldLength($fieldName, $text, $minLength, $maxLength){
        if($minLength <= strlen($text) && strlen($text) <= $maxLength){
            return true;
        }
        
        $this->error['inputFieldLength'] = 'The ' . $fieldName . ' must be between ' . $minLength . ' and ' . $maxLength . '.';
        return false;
    }
    
    function alphanumeric($fieldName, $text) {
        if(preg_match('/^\w+$/', $text)) {
            return true;
        }
        
        $this->error['alphanumeric'] = 'The ' . $fieldName . ' have to contain only letters and numbers.';
        return false;
    }
}

