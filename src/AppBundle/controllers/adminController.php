<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;
use StandardBundle\Models\User as User;

final class AdminController extends Controller {
    use \AppBundle\Traits\AdminTrait;
    use \StandardBundle\Traits\SecurityTrait;

    public function indexAction() {
        $this->canAdmin();
        $this->render('Admin');
    }

    public function usersAction() {
        $this->canAdmin('admin.users');
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        $alert = $_SESSION['alert'] ?? null;
        unset($_SESSION['alert']);
        $this->render('Users', [
            'error' => $error,
            'alert' => $alert
        ]);
    }

    public function userAction($request) {
        $this->canAdmin('admin.users');
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        $alert = $_SESSION['alert'] ?? null;
        unset($_SESSION['alert']);
        $user = \StandardBundle\Models\User::selectOneByPk($request['email']);
        $this->render('User', [
            'error' => $error,
            'alert' => $alert,
            'user' => $user
        ]);
    }

    // public function userAddAction() {
    //     $this->canAdmin('admin.users.add');
    //     $this->render('UserAdd');
    // }

    public function userEditAction($request) {
        $this->canAdmin('admin.users.edit');
        $user = \StandardBundle\Models\User::selectOneByPk($request['email']);
        $this->render('UserEdit', [
            'user' => $user
        ]);
    }

    public function userEditPostAction($request) {
        $this->canAdmin('admin.users.edit');
        $user = \StandardBundle\Models\User::selectOneByPk($request['email']);
        if (!(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['birthday']) && isset($_POST['gender']) && isset($_POST['group']) && isset($_POST['active']))) {
            $_SESSION['error'] = 'Tous les champs sont obligatoires';
            $this->redirect('/admin/user/' . $user->getPk());
        }
        $email = $_POST['email'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];
        $group = $_POST['group'];
        $active = $_POST['active'];
        if (User::selectOneByPk($email)) {
            $_SESSION['error'] = 'Un utilisateur avec cette adresse mail existe déjà.';
            $this->redirect('/admin/user/' . $user->getPk());
        }
        if ($password != $user->get('password')) 
            $password = SecurityTrait::class::hash($password);

        $userDeleted = $user->delete();
        $newuser = User::create($email, $password, $firstname, $lastname, $birthday, $gender, $group, $active);
        if ($_SESSION['user']->getPk() == $user->getPk()) {
            $_SESSION['user'] = $newuser;
        }
        
        switch([$userDeleted, $newuser]) {
            case [false, false]:
                $_SESSION['alert'] = 'L\'utilisateur n\'a pas pu être modifié';
                break;
            case([false, $newuser]):
                $_SESSION['alert'] = 'Le nouvel utilisateur a été créé mais l\'ancien n\'a pas pu être supprimé';
                break;
            case([$userDeleted, false]):
                $_SESSION['alert'] = 'L\'ancien utilisateur a été supprimé mais le nouveau n\'a pas pu être créé';
                break;
            default:
                $_SESSION['alert'] = 'L\'utilisateur a été modifié avec succès';        
        }
        $this->redirect('/admin/user/' . $user->getPk());
    }

}