<?php

namespace Framework\Core;

abstract class View {
    private $data = [];

    public function __construct(array $data = []) {
        $this->data = $data;
        $this->data['viewName'] = get_class($this);
        $this->render();
    }

    protected function get(string $key) {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            throw new \Exception("La variable $key n'existe pas.", 503);
        }
    }

    abstract protected function render();

}
