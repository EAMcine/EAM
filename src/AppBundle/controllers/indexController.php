<?php

namespace AppBundle\controllers;

class indexController {

    public static function indexAction() {
        $title = 'Accueil';
        $linkedPages = [
            'About' => '/about',
            'Contact' => '/contact',
            'Login' => '/login'
        ];
        $data = array(
            'title' => $title,
            'linkedPages' => $linkedPages
        );
        render('/index.view.php', $data);
    }

}