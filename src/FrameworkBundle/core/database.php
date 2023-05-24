<?php

namespace Framework\Core;

class Database extends \PDO {
    
    private static $instance = null;

    private function __construct() {
        parent::__construct('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
    }

    public static function getDb() : Database {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
}