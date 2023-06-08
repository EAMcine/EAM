<?php

namespace AppBundle\Controllers;

use AppBundle\Controllers\DefaultController as Controller;
use StandardBundle\Models\User as User;
use StandardBundle\Traits\SecurityTrait as SecurityTrait;

final class AdminController extends Controller {
    use \AppBundle\Traits\AdminTrait;

    public function indexAction() {
        $this->canAdmin();
        $this->render('Admin');
    }

    public function usersAction() {
        $this->canAdmin('users');
        $this->render('Users', [
            'users' => User::selectAll()
        ]);
    }

    public function userAction($request) {
        $this->canAdmin('users');
        $user = \StandardBundle\Models\User::selectOneByPk($request['email']);
        $this->render('User', [
            'user' => $user
        ], 'Utilisateur : ' . $user->getPk());
    }

    public function userAddAction() {
        $this->canAdmin('users.add');
        $groups = \StandardBundle\Models\Group::selectAll();
        $groupsOptions = [];
        foreach ($groups as $group) {
            $groupsOptions[$group->getPk()] = $group->get('name');
        }
        $this->render('UserAdd',[
            'groupsOptions' => $groupsOptions
        ]);
    }

    public function userAddPostAction() {
        $this->canAdmin('users.add');
        if (!(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['birthday']) && isset($_POST['gender']) && isset($_POST['group']) && isset($_POST['active']))) {
            $_SESSION['error'] = 'Tous les champs sont obligatoires';
            $this->redirect('/admin/user/add');
        }
        if (User::selectOneByPk($_POST['email'])) {
            $_SESSION['error'] = 'Cet utilisateur existe déjà';
            $this->redirect('/admin/user/add');
        }    
        $email = $_POST['email'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];
        $group = $_POST['group'];
        $active = $_POST['active'];
        $user = User::create($email, $password, $firstname, $lastname, $birthday, $gender, $group, $active);
        if ($user) {
            $_SESSION['alert'] = 'Utilisateur créé avec succès';
            $this->redirect('/admin/user/' . $email);
        } else {
            $_SESSION['error'] = 'Erreur lors de la création de l\'utilisateur';
            $this->redirect('/admin/user/add');
        }
    }

    public function userEditAction($request) {
        $this->canAdmin('users.edit');
        $user = \StandardBundle\Models\User::selectOneByPk($request['email']);
        $groups = \StandardBundle\Models\Group::selectAll();
        $groupsOptions = [];
        foreach ($groups as $group) {
            $groupsOptions[$group->getPk()] = $group->get('name');
        }
        $this->render('UserEdit', [
            'user' => $user,
            'groupsOptions' => $groupsOptions,
        ], 'Modifier l\'utilisateur ' . $user->getPk());
    }

    public function userEditPostAction($request) {
        $this->canAdmin('users.edit');
        $email = $request['email'];
        $user = \StandardBundle\Models\User::selectOneByPk($email);
        if (!(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['birthday']) && isset($_POST['gender']) && isset($_POST['group']) && isset($_POST['active']))) {
            $_SESSION['error'] = 'Tous les champs sont obligatoires';
            $this->redirect('/admin/user/' . $user->getPk() . '/edit');
        }
        $newemail = $_POST['email'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];
        $group = $_POST['group'];
        $active = $_POST['active'];
        if ($email != $newemail && User::selectOneByPk($newemail)) {
            $_SESSION['error'] = 'Un utilisateur avec cette adresse mail existe déjà.';
            $this->redirect('/admin/user/' . $user->getPk() . '/edit');
        }
        if ($password != $user->get('password')) 
            $password = SecurityTrait::hash($password);

        $user->setPk($newemail);
        $user->set('password', $password);
        $user->set('firstname', $firstname);
        $user->set('lastname', $lastname);
        $user->set('birthday', $birthday);
        $user->set('gender', $gender);
        $user->set('group', $group);
        $user->set('active', $active);
        $success = $user->update();
        if ($_SESSION['user']->getPk() == $user->getPk()) {
            $_SESSION['user'] = $user;
        }
        
        switch($success) {
            case 0:
                $_SESSION['alert'] = 'Aucune modification n\'a été effectuée.';
                break;
            default:
                $_SESSION['alert'] = 'L\'utilisateur a bien été modifié.';
                break;
        }
        $this->redirect('/admin/user/' . $user->getPk());
    }
}
