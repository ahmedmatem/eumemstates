<?php

class DB{
    private static $_instance; //The single instance
    private $_connection;
    
    private $_host = "localhost";
    private $_username = "root";
    private $_password = "qwerty";
    private $_database = "eudata";

    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance() {
            if(!self::$_instance) { // If no instance then make one
                    self::$_instance = new self();
            }
            return self::$_instance;
    }

    // Constructor
    private function __construct() {
            $this->_connection = new mysqli($this->_host, $this->_username, 
                    $this->_password, $this->_database);

            // Error handling
            if(mysqli_connect_error()) {
                    die("Failed to conencto to MySQL: " . mysqli_connect_errno());
            }
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }

    // Get mysqli connection
    public function getConnection() {
            return $this->_connection;
    }
}

