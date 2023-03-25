<?php

namespace ApiBundle\Traits;

class databaseTrait extends \PDO {

    public function __construct()
    {
        $this = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
    }

    public function getDb()
    {
        return $this;
    }

    public function setDb($db)
    {
        $this = $db;
    }

    public function __destruct()
    {
        $this = null;
    }
}