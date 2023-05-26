<?php

namespace StandardBundle\Models;

use \Framework\Components\Model as Model;
use \Framework\Core\Database as Database;
use \StandardBundle\Traits\SecurityTrait as SecurityTrait;

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

        if ($group instanceof Group) {
            $group = $group->get('code');
        }

        $data = [
            'email' => $email,
            'password' => $password,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'group' => $group,
            'active' => 0
        ];

        if (self::selectOneByPk($email))
            return false;

        $user = new User($data);
        $user->insert();

        return $user;
    }

    public static function select(string $where = null, array $params = null) : array|false {
        $result = Database::select(static::getTable(), $where, $params);
        if ($result == false) {
            return false;
        }
        $users = array();
        foreach ($result as $user) {
            $users[] = new self($user);
        }
        return $users;
    }

    public static function selectOne(string $where = null, array $params = null) : self|false {
        $result = Database::selectOne(static::getTable(), $where, $params);
        if ($result == false) {
            return false;
        }
        return new self($result);
    }

    public static function selectOneByPk(mixed $pk) : self|false {
        $result = Database::selectOneByPk(static::getTable(), $pk, static::getPkName());
        if ($result == false) {
            return false;
        }
        return new self($result);
    }

    public static function selectAll() : array|false {
        $result = Database::selectAll(static::getTable());
        if ($result == false) {
            return false;
        }
        $users = array();
        foreach ($result as $user) {
            $users[] = new self($user);
        }
        return $users;
    }

    public static function initTable() : string {
        return 'CREATE TABLE IF NOT EXISTS `users` (
            `email` varchar(320) NOT NULL,
            `password` varchar(60) NOT NULL,
            `firstname` varchar(255) NOT NULL,
            `lastname` varchar(255) NOT NULL,
            `group` varchar(255) NOT NULL,
            `active` tinyint(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`email`)
            )';
    }

}
