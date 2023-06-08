<?php

namespace Framework\Components;

use \tidy as tidy;

abstract class Controller {
    protected array $_data = [];

    protected function __construct() {
        $this->_data = [];                
    }

    protected function render(string $viewName, array $data = [], string $viewTitle = null) {
        $viewName = 'AppBundle\\Views\\' . $viewName;
        new $viewName(array_merge($this->_data, $data), $viewTitle);
        $output = ob_get_clean();
        $tidyConfig = array(
          'indent' => true,
          'indent-spaces' => 4,
          'wrap' => 0,
          'output-xhtml' => true,
          'show-errors' => 0,
          'drop-empty-elements' => false,
        );
        $tidy = new tidy();
        $tidy->parseString($output, $tidyConfig);
        $tidy->cleanRepair();
        echo $tidy;
    }

    protected function set(mixed $key, mixed $value) : void {
        $this->_data[$key] = $value;
    }

    public function redirect(string $url) {
        // set HTTP method to GET for redirection
        $_SERVER['REQUEST_METHOD'] = 'GET';
        header("Location: $url");
        die();
    }

    public function json(mixed $data) {
        header('Content-Type: application/json');
        echo json_encode(array_merge($this->_data, $data));
    }

    public function error(int $code, string $message) {
        http_response_code($code);
        echo $code . ' : ' . $message;
    }
}
