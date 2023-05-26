<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;
use StandardBundle\Models\User as User;
use StandardBundle\Traits\SecurityTrait as SecurityTrait;
use StandardBundle\Models\Group as Group;

final class UserController extends Controller {

    public function loginAction() {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        $this->render('Login', array(
            'error' => $error
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
        $this->redirect('/login/');
    }

    public function logoutAction() {
        unset($_SESSION['user']);
        $this->redirect('/');
    }

    public function registerAction() {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        $this->render('Register', array(
            'error' => $error
        ));
    }

    public function registerPostAction() {
        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['firstname']) && isset($_POST['lastname'])) {
            if ($_POST['password'] === $_POST['password2']) {
                $user = User::selectOneByPk($_POST['email']);
                if (!$user) {
                    $user = User::create(
                        array(
                            'email' => $_POST['email'],
                            'password' => SecurityTrait::hash($_POST['password']),
                            'firstname' => $_POST['firstname'],
                            'lastname' => $_POST['lastname'],
                            'group' => Group::selectOneByPk('user'),
                            'active' => 0
                        )
                    );
                    $_SESSION['user'] = $user;
                    $this->redirect('/');
                } else {
                    $_SESSION['error'] = 'Utilisateur déjà existant';
                }
            } else {
                $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
            }
        } else {
            $_SESSION['error'] = 'Veuillez remplir tous les champs';
        }
        $this->redirect('/register/');
    }

    public function connectAction() {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            
        }
    }
}
