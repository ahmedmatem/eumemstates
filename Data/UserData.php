<?php

class UserData{
    private $_users = array();
    private $_con;
    
    public function __construct() {
        $db = DB::getInstance();
        $this->_con = $db->getConnection();
    }
    
    public function getAllUsers(){
        $query = 'SELECT * FROM users';        
        $result = $this->_con->query($query);
        while($row = mysqli_fetch_assoc($result)) {
            $user = new User();
            $user->userId = $row['UserId'];
            $user->username = $row['Username'];
            $user->role = $row['Role'];
            
            $this->_users[] = $user;
        }
        
        return $this->_users;
    }
    
    public function getUserById($id) {
        $user = new User();
        
        $query = 'SELECT * FROM users WHERE userId="' . $id . '"';        
        $result = $this->_con->query($query);
        while($row = mysqli_fetch_assoc($result)) {
            $user->userId = $row['UserId'];
            $user->username = $row['Username'];
            $user->role = $row['Role'];
            break;
        }
        
        return $user;
    }
}

