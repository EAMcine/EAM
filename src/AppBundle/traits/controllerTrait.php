<?php

namespace AppBundle\Traits;

trait ControllerTrait {
    
    protected function sessionInfo() {
        if (isset($_SESSION['error'])) {
            $this->set('error', '<p class="error">' . $_SESSION['error'] . '</p>');
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['alert'])) {
            $this->set('alert', '<p class="alert">' . $_SESSION['alert'] . '</p>');
            unset($_SESSION['alert']);
        }
        if (isset($_SESSION['success'])) {
            $this->set('success', '<p class="success">' . $_SESSION['success'] . '</p>');
            unset($_SESSION['success']);
        }
    }
}
