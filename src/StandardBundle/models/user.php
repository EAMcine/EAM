<?php

namespace StandardBundle\Models;

use Framework\Components\Model as Model;

final class User extends Model {
    protected static string $_pkName = 'email';
    protected static string $_table = 'users';

}
