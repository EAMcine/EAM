<?php

namespace AppBundle\Controllers;

use AppBundle\Controllers\DefaultController as Controller;
use StandardBundle\Models\User as User;
use StandardBundle\Models\UserToken as UserToken;
use StandardBundle\Traits\SecurityTrait as SecurityTrait;
use StandardBundle\Models\Group as Group;

final class UserController extends Controller {

    public function loginAction() {
        if (isset($_SESSION['user'])) {
            $this->set('alert', 'Vous êtes déjà connecté');
            $this->redirect('/account');
        }
        $this->render('Login');
    }

    public function loginPostAction() {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $user = User::selectOneByPk($_POST['email']);
            if ($user) {
                if (SecurityTrait::verify($_POST['password'], $user->get('password'))) {
                    $_SESSION['user'] = $user;
                    $this->redirect('/account');
                } else {
                    $_SESSION['error'] = 'Mot de passe incorrect';
                }         
            } else {
                $_SESSION['error'] = 'Utilisateur introuvable';
            }
        } else {
            $_SESSION['error'] = 'Veuillez remplir tous les champs';
        }
        $this->redirect('/login');
    }

    public function logoutAction() {
        unset($_SESSION['user']);
        $this->redirect('/');
    }

    public function registerAction() {
        $this->render('Register');
    }

    public function registerPostAction() {
        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordConfirm']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['birthday'])&& isset($_POST['gender'])) {
            if ($_POST['password'] === $_POST['passwordConfirm']) {
                $user = User::create(
                    $_POST['email'], 
                    SecurityTrait::hash($_POST['password']), 
                    $_POST['firstname'], 
                    $_POST['lastname'], 
                    $_POST['birthday'], 
                    $_POST['gender'], 
                    'user'
                );
                if ($user) {
                    $token = UserToken::create($_POST['email']);
                    // TODO: Send email with activation token (/confirm/{email}/{token})
                    $_SESSION['alert'] = 'Utilisateur créé avec succès, veuillez confirmer votre compte via le lien envoyé par email';
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
        $this->redirect('/register');
    }

    public function confirmAction($request) {
        if (isset($request['token']) && isset($request['email'])) {
            $token = UserToken::selectOneByPk($request['email']);
            if ($token) {
                if ($token->get('token') === $request['token']) {
                    $user = User::selectOneByPk($request['email']);
                    if ($user) {
                        $user->set('active', 1);
                        $user->save();
                        $token->delete();
                        $_SESSION['alert'] = 'Utilisateur activé avec succès';
                    } else {
                        $_SESSION['error'] = 'Utilisateur introuvable';
                    }
                } else {
                    $_SESSION['error'] = 'Token invalide';
                }
            } else {
                $_SESSION['error'] = 'Token introuvable';
            }
        } else {
            $_SESSION['error'] = 'Veuillez remplir tous les champs';
        }
        $this->redirect('/account');
    } 

    public function accountAction() {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            $_SESSION['error'] = 'Veuillez vous connecter pour accéder à votre compte';
            $this->redirect('/login');
        }
        if (!$user->get('active')) {
            $_SESSION['error'] = 'Veuillez activer votre compte pour accéder à votre compte';
            $this->redirect('/login');
        }
        $group = Group::selectOneByPk($user->get('group'));
        $this->render('Account', array(
            'user' => $user,
            'group' => $group
        ));
    }
}
