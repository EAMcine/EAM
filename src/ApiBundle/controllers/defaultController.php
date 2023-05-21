<?php

namespace ApiBundle\Controllers;

use Framework\Core\Controller as Controller;

final class defaultController extends Controller {

    public function redirectAction() {
        header('Location: /');
    }

    public function pingAction() {    
        $this->json(array(
            'status' => 200,
            'message' => 'pong',
            'server_time' => microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]
        ));
    }
}
