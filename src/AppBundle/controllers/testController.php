<?php

namespace AppBundle\controllers;

use AppBundle\Controllers\DefaultController as Controller;
use StandardBundle\Traits\BddTrait as BddTrait;

class TestController extends Controller {
    use BddTrait;

    public function bddInitAction() {
        BddTrait::bddInit();
        $_SESSION['alert'] = 'Base de données initialisée';
        $this->redirect('/');
    }
}