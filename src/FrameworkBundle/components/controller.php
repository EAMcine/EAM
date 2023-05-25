<?php

namespace Framework\Components;

abstract class Controller {

    protected function render(string $viewName, array $data = [], string $viewTitle = null) {
        $viewName = 'AppBundle\\Views\\' . $viewName;
        new $viewName($data, $viewTitle);
    }

    public function redirect(string $url) {
        header("Location: $url");
    }

    public function json(mixed $data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function error(int $code, string $message) {
        http_response_code($code);
        echo $code . ' : ' . $message;
    }
}
