<?php

namespace AppBundle\Controllers;

use Framework\Core\Controller as Controller;
use Framework\Routing\Router as Router;

class defaultController extends Controller {

    public static function indexAction() {
        var_dump(Router::getRoutes());
    }

    public static function testAction() {
        echo 'test';
    }

    public static function errorAction() {
        echo '404';
    }

}