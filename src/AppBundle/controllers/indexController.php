<?php

namespace AppBundle\Controllers;

use AppBundle\Controllers\DefaultController as Controller;

final class IndexController extends Controller {

    public function indexAction() {
        $this->render('Index');
    }

    public function aboutAction() {
        $this->render('About');
    }

    public function contactAction() {
        $this->render('Contact');
    }

    public function contactPostAction() {
        // TODO: Save the contact form in the database
        $_SESSION['alert'] = 'Votre message a bien été envoyé';
        $this->redirect('/contact');
    }

    public function legalAction() {
        $this->render('Legal');
    }
    
    public function themePostAction() {
        if ($_SESSION['theme'] == 'light') {
            $_SESSION['theme'] = 'dark';
        } else {
            $_SESSION['theme'] = 'light';
        }
        $this->json(array(
            'status' => 200,
            'theme' => $_SESSION['theme']
        ));
    }

    public function errorAction() {
        $this->error(404, 'Page not found');
    }

}