<?php

namespace ApiBundle\Controllers;

use Framework\Core\Controller as Controller;

class defaultController extends Controller {

    public static function redirectAction() {
        header('Location: https://www.google.com');
    }

    public static function indexAction() {
        echo 'Hello World!';
    }
}
