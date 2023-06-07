<?php

namespace Framework\Components;

use \Framework\Core\Database as Database;

abstract class Model {

    protected array|null $_data;
    protected int|string|null $_pk;
    protected static string|null $_pkName;
    protected static string $_table;

    public function __construct(array $data = null) {
        $this->_data = $data;
        $this->_pk = $data[self::getPkName()] ?? null;
    }

    abstract protected static function create(mixed ...$args) : Model|false;
    abstract public static function select(string $where = null, array $params = null) : array|false;
    abstract public static function selectOne(string $where = null, array $params = null) : self|false;
    abstract public static function selectOneByPk(mixed $pk) : self|false;
    abstract public static function selectAll() : array|false;
    abstract protected static function initTable() : string;

    public function save() : int|false {
        if (static::getPkName() == null || static::getPkName() == 'id' || $this->_pk == null) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    protected function insert() : int|false {
        $db = Database::getDb();
        $fields = array_keys($this->_data);
        $values = array_values($this->_data);
        $fields = implode(', ', array_map(function($field) use ($db) {
            return "`$field`";
        }, $fields));
        $values = implode(', ', array_map(function($value) use ($db) {
            return isset($value) ? $db->quote($value) : 'NULL';
        }, $values));
        $query = "INSERT INTO `".static::getTable()."` ($fields) VALUES ($values)";
        return $db->exec($query);
    }

    public function update() : int|false {
        $db = Database::getDb();
        $fields = array_keys($this->_data);
        $values = array_values($this->_data);
        $fields = implode(', ', array_map(function($field, $value) use ($db) {
            return "`$field` = ".(isset($value) ? $db->quote($value) : 'NULL');
        }, $fields, $values));
        $query = "UPDATE `".static::getTable()."` SET $fields WHERE `".self::getPkName()."` = ".$db->quote($this->_pk);
        return $db->exec($query);
    }

    public function delete() : int|false {
        $db = Database::getDb();
        $query = "DELETE FROM `".static::getTable()."` WHERE `".self::getPkName()."` = ".$db->quote($this->_pk);
        return $db->exec($query);
    }

    public function __toString() : string {
        return json_encode($this->_data);
    }

    public function get(string|null $key = null) : mixed {
        if ($key == null) {
            return $this->_data;
        }
        return $this->_data[$key] ?? null;
    }

    public function set(string $key, mixed $value) : bool {
        if (array_key_exists($key, $this->_data)) {
            $this->_data[$key] = $value;
            return true;
        }
        return false;
    }

    public function setPk(mixed $pk) : bool {
        if (isset($this->_data[self::getPkName()])) {
            $this->_data[self::getPkName()] = $pk;
            $this->_pk = $pk;
            return true;
        }
        return false;
    }

    public function getData() : array {
        return $this->_data;
    }

    public function getPk() : mixed {
        return $this->_pk;
    }

    public static function getPkName() : string {
        return static::$_pkName ?? 'id';
    }

    public static function getTable() : string {
        return static::$_table;
    }
}
