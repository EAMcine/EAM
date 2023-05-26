<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;
use StandardBundle\Models\User as User;
use StandardBundle\Traits\SecurityTrait as SecurityTrait;

final class UserController extends Controller {

    public function loginAction() {
        $this->render('Login', array(
            'error' => $_SESSION['error'] ?? null
        ));
    }

    public function loginPostAction() {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $user = User::selectOneByPk($_POST['email']);
            if ($user) {
                if (SecurityTrait::verify($_POST['password'], $user->get('password'))) {
                    $_SESSION['user'] = $user;
                    $this->redirect('/');
                } else {
                    $_SESSION['error'] = 'Mot de passe incorrect';
                }         
            } else {
                $_SESSION['error'] = 'Utilisateur introuvable';
            }
        } else {
            $_SESSION['error'] = 'Veuillez remplir tous les champs';
        }
        die($_SESSION['error']);
        $this->redirect('/login/');
    }

    public function logoutAction() {
        unset($_SESSION['user']);
        $this->redirect('/');
    }

    public function registerAction() {
        $this->render('Register');
    }

    public function registerPostAction() {

    }

    public function connectAction() {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            
        }
    }
}
