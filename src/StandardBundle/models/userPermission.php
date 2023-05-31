<?php

namespace StandardBundle\Models;

use Framework\Components\Model as Model;
use Framework\Core\Database as Database;

final class UserPermission extends Model {
    protected static string $_table = 'users_permissions';

    /**
     * @param string $user
     * @param string $permission
     */
    public static function create(mixed ...$args) : UserPermission|false {
        if(count($args) != 2)
            return false;

        $user = $args[0];
        $permission = $args[1];

        $data = array(
            'user' => $user,
            'permission' => $permission
        );

        if (self::selectOne('`user` = ? AND `permission` = ?', array($user, $permission)))
            return false;

        $userPermission = new UserPermission($data);
        $userPermission->insert();

        return $userPermission;
    }

    public static function getPermissions(array $permissions) : array|false {
        $result = array();
        foreach ($permissions as $permission) {
            $result[] = $permission->get('permission');
        }
        return $result;
    }

    public static function select(string $where = null, array $params = null) : array|false {
        $result = Database::select(static::getTable(), $where, $params);
        if ($result == false) {
            return false;
        }
        $usersPermissions = array();
        foreach ($result as $userPermission) {
            $usersPermissions[] = new self($userPermission);
        }
        return $usersPermissions;
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

    public static function selectAll(): array|false {
        $result = Database::selectAll(static::getTable());
        if ($result == false) {
            return false;
        }
        $usersPermissions = array();
        foreach ($result as $userPermission) {
            $usersPermissions[] = new self($userPermission);
        }
        return $usersPermissions;
    }

    public static function initTable() : string {
        return 'CREATE TABLE IF NOT EXISTS `users_permissions` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user` varchar(320) NOT NULL,
            `permission` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            )';
    }
}
