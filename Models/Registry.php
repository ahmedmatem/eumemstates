<?php

class Registry {
    protected static $_data = array();
    
    public static function set($key, $value) {
        self::$_data[$key] = $value;
    }
    
    public static function get($key, $default = NULL) {
        if(array_key_exists($key, self::$_data)) {
            return self::$_data[$key];
        }
        
        return $default;
    }
}

