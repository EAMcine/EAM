<?php

namespace AppBundle\controllers;

use AppBundle\Controllers\DefaultController as Controller;

class InvoiceController extends Controller {
    use \AppBundle\Traits\AdminTrait;

    public function indexAction() {
        $this->canAdmin('invoices');
        $this->render('Invoice');
    }
}