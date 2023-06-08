<?php

namespace AppBundle\Traits;

trait ControllerTrait {
    
    protected function sessionInfo() {
        if (isset($_SESSION['error'])) {
            $this->set('error', $_SESSION['error']);
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['alert'])) {
            $this->set('alert', $_SESSION['alert']);
            unset($_SESSION['alert']);
        }
        if (isset($_SESSION['success'])) {
            $this->set('success', $_SESSION['success']);
            unset($_SESSION['success']);
        }
    }
}
