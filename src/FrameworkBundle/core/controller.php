<?php

namespace Framework\Core;

use Framework\Core\View as View;

class Controller {

    private $view;

    public function __construct() {
        $this->view = new View();
    }

    public function render(string $viewName, array $data = []) {
        $this->view->render($viewName, $data);
    }

    public function redirect(string $url) {
        header("Location: $url");
        exit();
    }

    public function json(array $data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function error(int $code, string $message) {
        http_response_code($code);
        echo $message;
        exit();
    }

}