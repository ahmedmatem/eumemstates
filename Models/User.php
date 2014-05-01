<?php

class User{
    public $userId;
    public $username;
    public $password;
    public $role;
    
    function getRoleAsString(){
        $result = '';
        
        switch ($this->role) {
            case 0:
                $result = 'Administrator';
                break;
            case 1:
                $result = 'Default';
                break;      
            default:
                break;
        }
        
        return $result;
    }
}

