<?php

namespace StandardBundle\Models;

use \Framework\Components\Model as Model;
use \Framework\Core\Database as Database;
use \StandardBundle\Traits\SecurityTrait as SecurityTrait;

final class User extends Model {
    protected static string|null $_pkName = 'email';
    protected static string $_table = 'users';
    private array|null $_permissions = null;
    
    /**
     * @param string $email
     * @param string $password
     * @param string $firstname
     * @param string $lastname
     * @param string $birthday
     * @param string $gender
     * @param string $group
     */
    public static function create(mixed ...$args) : User|false {
        if (count($args) != 7)
            return false;

        $email = $args[0];
        $password = $args[1];
        $firstname = $args[2];
        $lastname = $args[3];
        $birthday = $args[4];
        $gender = $args[5];
        $group = $args[6];

        if ($group instanceof Group) {
            $group = $group->get('code');
        }

        $data = [
            'email' => $email,
            'password' => $password,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'birthday' => $birthday,
            'gender' => $gender,
            'group' => $group,
            'active' => 0
        ];

        if (self::selectOneByPk($email))
            return false;

        $user = new User($data);
        $user->insert();

        $user->refreshPermissions();

        return $user;
    }

    private function getPermissions() : array {
        if ($this->_permissions == null) {
            $this->refreshPermissions();
        }
        return $this->_permissions;
    }

    private function refreshPermissions() : void {
        $this->_permissions = array();
        $usersPermissions = UserPermission::select('`user` = ?', array($this->get('email')));
        $groupsPermissions = GroupPermission::selectOneByPk($this->get('group'))->getPermissions();
        if ($usersPermissions == false && $groupsPermissions == false) {
            return;
        }
        if ($usersPermissions != false) {
            foreach ($usersPermissions as $userPermission) {
                $this->_permissions[] = $userPermission->get('permission');
            }
        }
        if ($groupsPermissions != false) {
            foreach ($groupsPermissions as $groupPermission) {
                $this->_permissions[] = $groupPermission->get('permission');
            }
        }
    }

    public function can(array|string $permissions) : bool {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if (!$this->can($permission)) {
                    return false;
                }
            }
            return true;
        } else {
            $permissionNode = explode('.', $permissions);
            while (count($permissionNode) > 0) {
                $permission = implode('.', $permissionNode) . '.*';
                if (in_array($permission, $this->getPermissions())) {
                    return true;
                }
                array_pop($permissionNode);
            }
            if (in_array('*', $this->getPermissions())) {
                return true;
            }
            return false;
        }
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
            `birthday` date NOT NULL,
            `gender` varchar(255) NOT NULL,
            `group` varchar(255) NOT NULL,
            `active` tinyint(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`email`)
            )';
    }

}
