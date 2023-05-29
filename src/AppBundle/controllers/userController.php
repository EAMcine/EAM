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
        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordConfirm']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['birthday'])&& isset($_POST['gender'])) {
            if ($_POST['password'] === $_POST['passwordConfirm']) {
                $user = User::create($_POST['email'], SecurityTrait::hash($_POST['password']), $_POST['firstname'], $_POST['lastname'], $_POST['birthday'], $_POST['gender'], 'user');
                if ($user) {
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
}
