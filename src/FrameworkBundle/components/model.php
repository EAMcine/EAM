<?php

namespace Framework\Components;

use \Framework\Core\Database as Database;

abstract class Model {

    protected array|null $data;
    protected int|null $id;
    protected string $table;

    protected function __construct(array $data = null) {
        $this->data = $data;
        $this->id = $data['id'] ?? null;
    }

    public static function create(array $data) : Model {
        $model = new static($data);
        $model->save();
        return $model;
    }

    protected function save() : bool {
        if ($this->id == null) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    protected function insert() : bool {
        $data = $this->data;
        unset($data['id']);
        return Database::save($this->table, $data);
    }

    protected function update() {
        $data = $this->data;
        $data['id'] = $this->id;
        return Database::update($this->table, $data, 'id = '.$this->id);
    }

    public function delete() {
        return Database::delete($this->table, 'id = '.$this->id);
    }

    public function select(string $where = null, array $params = null) : array|false {
        return Database::select($this->table, $where, $params);
    }

    public function selectOne(string $where = null, array $params = null) : array|false {
        return Database::selectOne($this->table, $where, $params);
    }

    public function getId() : int|null {
        return $this->id;
    }

    public function getTable() : string {
        return $this->table;
    }

    public function get(string|null $key = null) : mixed {
        if ($key == null) {
            return $this->data;
        }
        return $this->data[$key] ?? null;
    }

}
