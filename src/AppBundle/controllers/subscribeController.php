<?php

namespace AppBundle\controllers;

use AppBundle\Controllers\DefaultController as Controller;

class SubscribeController extends Controller {

    public function billingAction() {
        $this->render('Billing');
    }
}