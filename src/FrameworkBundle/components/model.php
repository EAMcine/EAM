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

    public function save() : bool {
        if (static::getPkName() == null || static::getPkName() == 'id' || $this->_pk == null) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    protected function insert() : bool {
        return Database::insert(static::getTable(), $this->_data);
    }

    protected function update() : bool {
        return Database::update(static::getTable(), $this->_data, self::getPkName(), $this->_pk);
    }

    public function delete() : bool {
        return Database::delete(static::getTable(), self::getPkName(), $this->_pk);
    }

    public function get(string|null $key = null) : mixed {
        if ($key == null) {
            return $this->_data;
        }
        return $this->_data[$key] ?? null;
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
