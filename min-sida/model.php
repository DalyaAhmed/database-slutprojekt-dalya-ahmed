<?php

require_once "database.php";

class Model {

    private static $connection = null;

   
    private function __construct() {
        try {
            self::getConnection(); 
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }


   public static function getConnection() {
    if (self::$connection == null) {
        self::$connection = getDatabaseConnection();
    }

    return self::$connection;
}

  
    public static function getInstance() {
        return new self();
    }
}

?>