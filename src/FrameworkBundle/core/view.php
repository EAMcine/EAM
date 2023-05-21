<?php

namespace Framework\Core;

class View {

    private $viewPath;
    private $data = [];

    public function __construct(string $viewPath = null, array $data = []) {
        if ($viewPath == null) {
            $viewPath = __DIR__ . '/../../views/';
        }
        $this->viewPath = $viewPath;
        $this->data = $data;
    }

    public function render(string $viewName, array $data = []) {
        $this->data = $data;
        $viewPath = $this->viewPath . $viewName;
        if (file_exists($viewPath)) {
            include_once $viewPath;
        } else {
            throw new \Exception("La vue $viewName n'existe pas.", 503);
        }
    }

    public function get(string $key) {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            throw new \Exception("La variable $key n'existe pas.", 503);
        }
    }

    public function set(string $key, $value) {
        $this->data[$key] = $value;
    }

}
