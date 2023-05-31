<?php

namespace AppBundle\Traits;

trait DebugTrait {

    public function canDebug() {
        return ENV_NAME === 'dev' || $_SESSION['user']->can('DEBUG');
    }
}
