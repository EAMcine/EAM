<?php

namespace AppBundle\controllers;

use Framework\Components\Controller as Controller;

class InvoiceController extends Controller {
    use \AppBundle\Traits\AdminTrait;

    public function indexAction() {
        $this->canAdmin('invoices');
        $this->render('Invoice');
    }
}