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

    public static function save($model) : bool {
        if (!($model instanceof \Framework\Components\Model)) {
            throw new \Exception('Le paramètre doit être une instance de Model.', 500);
        }
        $data = $model->get();
        if ($model->getId() == null) {
            return self::insert($model->getTable(), $data);
        } else {
            return self::update($model->getTable(), $data, 'id = '.$model->getId());
        }
    }

    public static function insert(string $table, array $data) : bool {
        $db = self::getDb();
        $columns = array_keys($data);
        $values = array_values($data);
        $sql = 'INSERT INTO '.$table.' ('.implode(', ', $columns).') VALUES ('.implode(', ', array_fill(0, count($columns), '?')).')';
        $stmt = $db->prepare($sql);
        return $stmt->execute($values);
    }

    public static function update(string $table, array $data, string $where) : bool {
        $db = self::getDb();
        $columns = array_keys($data);
        $values = array_values($data);
        $sql = 'UPDATE '.$table.' SET '.implode(' = ?, ', $columns).' = ? WHERE '.$where;
        $stmt = $db->prepare($sql);
        return $stmt->execute($values);
    }

    public static function delete(string $table, string $where) : bool {
        $db = self::getDb();
        $sql = 'DELETE FROM '.$table.' WHERE '.$where;
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }

    public static function select(string $table, string $where = null, array $params = null) : array|false {
        $db = self::getDb();
        $sql = 'SELECT * FROM '.$table;
        if ($where != null) 
            $sql .= ' WHERE '.$where;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function selectOne(string $table, string $where = null, array $params = null) : array|false {
        $db = self::getDb();
        $sql = 'SELECT * FROM '.$table;
        if ($where != null) 
            $sql .= ' WHERE '.$where;
        $sql .= ' LIMIT 1';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}
