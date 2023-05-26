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
        if ($this->_pk == null) {
            $this->save();            
        }
    }

    abstract protected static function create(mixed ...$args) : Model|false;

    abstract protected static function initTable() : string;

    protected function save() : bool {
        if ($this->_pk == null) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    protected function insert() : bool {
        $data = $this->_data;
        unset($data['pk']);
        return Database::save($this->_table, $data);
    }

    protected function update() : bool {
        $data = $this->_data;
        $data['id'] = $this->_pk;
        return Database::update($this->_table, $data, 'id = '.$this->_pk);
    }

    public function delete() : bool {
        return Database::delete($this->_table, 'id = '.$this->_pk);
    }

    public static function select(string $where = null, array $params = null) : array|false {
        $result = Database::select(self::$_table, $where, $params);
        if ($result == false) {
            return false;
        }
        $models = [];
        foreach ($result as $data) {
            $models[] = new self($data);
        }
        return $models;
    }

    public static function selectOne(string $where = null, array $params = null) : self|false {
        $result = Database::selectOne(self::$_table, $where, $params);
        if ($result == false) {
            return false;
        }
        return new self($result);
    }

    public static function selectOneByPk(mixed $pk) : self|false {
        $result = Database::selectOne(self::getTable(), self::getPkName() .' = :'. self::getPkName(), [''.self::getPkName() => $pk]);
        if ($result == false) {
            return false;
        }
        return new self($result);
    }

    public static function selectAll() : array|false {
        $result = Database::select(self::$_table);
        if ($result == false) {
            return false;
        }
        $models = [];
        foreach ($result as $data) {
            $models[] = new self($data);
        }
        return $models;
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

    public function get(string|null $key = null) : mixed {
        if ($key == null) {
            return $this->_data;
        }
        return $this->_data[$key] ?? null;
    }

}
