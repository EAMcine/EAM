<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;
use Framework\Routing\Router as Router;
use Framework\Routing as Routing;

final class DefaultController extends Controller {

    public function indexAction() {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        $alert = $_SESSION['alert'] ?? null;
        unset($_SESSION['alert']);
        $this->render('Index', [
            'error' => $error,
            'alert' => $alert
        ]);
    }

    public function testAction($request) {
        echo 'test ' . $request['id'];
    }

    public function errorAction() {
        $this->error(404, 'Page not found');
    }

}