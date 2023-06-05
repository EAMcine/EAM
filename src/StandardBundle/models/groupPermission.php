<?php

namespace StandardBundle\Models;

use Framework\Components\Model as Model;
use Framework\Core\Database as Database;

final class GroupPermission extends Model {
    protected static string $_table = 'groups_permissions';

    /**
     * @param string $group
     * @param string $permission
     */
    public static function create(mixed ...$args) : GroupPermission|false {
        if(count($args) != 2)
            return false;

        $group = $args[0];
        $permission = $args[1];

        if ($group instanceof Group) {
            $group = $group->get('code');
        }
        
        if ($permission instanceof Permission) {
            $permission = $permission->get('code');
        }

        $data = array(
            'group' => $group,
            'permission' => $permission
        );

        if (self::selectOne('`group` = :group AND `permission` = :permission', $data))
            return false;

        $groupPermission = new GroupPermission($data);
        $groupPermission->insert();

        return $groupPermission;
    }

    public static function select(string $where = null, array $params = null) : array|false {
        $result = Database::select(static::getTable(), $where, $params);
        if ($result == false) {
            return false;
        }
        $groupsPermissions = array();
        foreach ($result as $groupPermission) {
            $groupsPermissions[] = new self($groupPermission);
        }
        return $groupsPermissions;
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
        $groupsPermissions = array();
        foreach ($result as $groupPermission) {
            $groupsPermissions[] = new self($groupPermission);
        }
        return $groupsPermissions;
    }

    public static function initTable() : string {
        return 'CREATE TABLE IF NOT EXISTS `groups_permissions` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `group` varchar(255) NOT NULL,
            `permission` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            )';
    }
}
