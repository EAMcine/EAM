<?php

namespace AppBundle\Traits;

trait ViewTrait {

    protected function sessionInfo() {
        if ($this->get('error')) {
            echo '<p class="error">' . $this->get('error') . '</p>';
        }
        if ($this->get('alert')) {
            echo '<p class="alert">' . $this->get('alert') . '</p>';
        }
        if ($this->get('success')) {
            echo '<p class="success">' . $this->get('success') . '</p>';
        }
    }
}