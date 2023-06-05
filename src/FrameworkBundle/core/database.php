<?php

namespace Framework\Core;

final class Database extends \PDO {
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

    public static function select(string $table, string $where = null, array $params = null) : array|false {
        $db = self::getDb();
        $sql = 'SELECT * FROM `'.$table.'`';
        if ($where != null) 
            $sql .= ' WHERE '.$where;
        $sql .= ';';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function selectOne(string $table, string $where = null, array $params = null) : array|false {
        $db = self::getDb();
        $sql = 'SELECT * FROM `'.$table.'`';
        if ($where != null) 
            $sql .= ' WHERE '.$where;
        $sql .= ' LIMIT 1;';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function selectOneByPk(string $table, mixed $pk, string $pkName) : array|false {
        $db = self::getDb();
        $sql = 'SELECT * FROM `'.$table.'` WHERE `'. $pkName .'` = ? LIMIT 1;';
        $stmt = $db->prepare($sql);
        $stmt->execute([$pk]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function selectAll(string $table) : array|false {
        $db = self::getDb();
        $sql = 'SELECT * FROM `'.$table.'`;';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
