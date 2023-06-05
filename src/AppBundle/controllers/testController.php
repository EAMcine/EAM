<?php

namespace AppBundle\controllers;

use Framework\Components\Controller as Controller;
use StandardBundle\Traits\BddTrait as bddTrait;

class testController extends Controller {

    public function bddInitAction() {
        bddTrait::bddInit();
        $_SESSION['alert'] = 'Base de données initialisée';
        $this->redirect('/');
    }
}