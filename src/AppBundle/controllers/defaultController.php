<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;

class DefaultController extends Controller {
    use \AppBundle\Traits\ControllerTrait;

    public function __construct() {
        parent::__construct();
        $this->sessionInfo();
    }
}
