<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;
use \AppBundle\Traits\ControllerTrait as ControllerTrait;

class DefaultController extends Controller {
    use ControllerTrait;

    public function __construct() {
        $this->sessionInfo();
    }
}
