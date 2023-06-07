<?php

namespace AppBundle\controllers;

use AppBundle\Controllers\DefaultController as Controller;
use StandardBundle\Traits\BddTrait as bddTrait;

class TestController extends Controller {

    public function bddInitAction() {
        bddTrait::bddInit();
        $_SESSION['alert'] = 'Base de données initialisée';
        $this->redirect('/');
    }
}