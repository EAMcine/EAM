<?php

namespace StandardBundle\Models;

use \Framework\Components\Model as Model;

final class User extends Model {
    protected static string|null $_pkName = 'email';
    protected static string $_table = 'users';
    
    /**
     * @param string $email
     * @param string $password
     * @param string $firstname
     * @param string $lastname
     * @param string $group
     */
    public static function create(mixed ...$args) : User|false {
        if (count($args) != 5)
            return false;

        $email = $args[0];
        $password = $args[1];
        $firstname = $args[2];
        $lastname = $args[3];
        $group = $args[4];

        $data = [
            'email' => $email,
            'password' => $password,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'group' => $group
        ];

        return new User($data);
    }

    public static function initTable() : string {
        return 'CREATE TABLE IF NOT EXISTS `users` (
            `email` varchar(320) NOT NULL,
            `password` varchar(60) NOT NULL,
            `firstname` varchar(255) NOT NULL,
            `lastname` varchar(255) NOT NULL,
            `group` int(11) NOT NULL,
            `active` tinyint(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`email`)
            )';
    }

}
