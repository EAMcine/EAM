<?php

namespace AppBundle\Traits;
use StandardBundle\Models\User as User;

trait UserTrait {

    protected function getSessionUser() : null|User {
        return $_SESSION['user'] ?? null;
    }
}