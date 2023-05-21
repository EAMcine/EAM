<?php

namespace Framework\Core;

use Framework\Core\View as View;

abstract class Controller {
    private $view;

    public function render(string $viewName, array $data = []) {
        $viewName = 'AppBundle\\Views\\' . $viewName;
        $this->view = new $viewName($data);

    }

    public function redirect(string $url) {
        header("Location: $url");
    }

    public function json(array $data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function error(int $code, string $message) {
        http_response_code($code);
        echo $code . ' : ' . $message;
    }
}
