<?php

namespace AppBundle\Traits;

trait DebugTrait {

    public function canDebug(string|null $permissionNode = null) {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page';
            $this->redirect('/login/');
        }
        $user = $_SESSION['user'];
        $user = $user->refreshUser();
        $permissionNode = 'debug.' . $permissionNode ?? 'debug';
        if (!$user->can($permissionNode)) {
            $_SESSION['error'] = 'Vous n\'avez pas les droits pour accéder à cette page';
            $this->redirect('/');
        }
    }
}
