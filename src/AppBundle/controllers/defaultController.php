<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;
use Framework\Routing\Router as Router;
use Framework\Routing as Routing;

final class DefaultController extends Controller {

    public function indexAction() {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        $alert = $_SESSION['alert'] ?? null;
        unset($_SESSION['alert']);
        $this->render('Index', [
            'error' => $error,
            'alert' => $alert
        ]);
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
        $this->redirect('/contact/');
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