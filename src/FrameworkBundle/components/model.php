<?php

namespace Framework\Components;

use \Framework\Core\Database as Database;

abstract class Model {

    protected array|null $_data;
    protected int|string|null $_pk;
    protected static string|null $_pkName;
    protected static string $_table;

    protected function __construct(array $data = null) {
        $this->_data = $data;
        $this->_pk = $data['pk'] ?? null;
    }

    public static function create(array $data) : Model {
        $model = new self($data);
        $model->save();
        return $model;
    }

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

    protected function update() {
        $data = $this->_data;
        $data['id'] = $this->_pk;
        return Database::update($this->_table, $data, 'id = '.$this->_pk);
    }

    public function delete() {
        return Database::delete($this->_table, 'id = '.$this->_pk);
    }

    public function select(string $where = null, array $params = null) : array|false {
        return Database::select($this->_table, $where, $params);
    }

    public function selectOne(string $where = null, array $params = null) : array|false {
        return Database::selectOne($this->_table, $where, $params);
    }

    public function getPk() : int|null {
        return $this->_pk;
    }

    public static function getPkName() : string {
        return self::$_pkName ?? 'id';
    }

    public static function getTable() : string {
        return self::$_table;
    }

    public function get(string|null $key = null) : mixed {
        if ($key == null) {
            return $this->_data;
        }
        return $this->_data[$key] ?? null;
    }

}
