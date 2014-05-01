<?php

class Country{
    public static $formatedCapitalMinLength =4;
    public static $formatedCapitalMaxLength = 30;
    
    public $id;
    public $name;
    public $capital;
    public $population;
    public $state;
    public $flag;
    
    public function getFormatedCapital($length) {
        if($length < self::$formatedCapitalMinLength ||
                self::$formatedCapitalMaxLength < $length) {
            // invalid length range. Do nothing.
            return $this->capital;
        }
        
        $capitalLength = strlen($this->capital);
        
        if($capitalLength <= $length){
            return $this->capital;
        }
        else{
            return substr($this->capital, 0, $length - 3) . '...';
        }
    }
    
    public function getStateAsString() {
        $result = '';
        switch ($this->state) {
            case 'v':
                $result = 'visible';
                break;
            case 'h':
                $result = 'hidden';
                break;
            default:
                break;
        }
        
        return $result;
    }
}

