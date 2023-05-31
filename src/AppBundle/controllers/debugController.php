<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;

final class DebugController extends Controller {
    use \AppBundle\Traits\DebugTrait;

    public function indexAction() {

        $this->render('Debug');
    }

}