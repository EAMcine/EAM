<?php

namespace StandardBundle\Models;

use Framework\Components\Model as Model;
use Framework\Core\Database as Database;

final class Group extends Model {
    protected static string|null $_pkName = 'code';
    protected static string $_table = 'groups';
    
    /**
     * @param string $code
     * @param string $name
     * @param string $description
     * @param Group|null $subgroup
     */
    public static function create(mixed ...$args) : Group|false {
        if(count($args) < 3 || count($args) > 4)
            return false;

        $code = $args[0];
        $name = $args[1];
        $description = $args[2];
        $subgroup = $args[3] ?? null;

        if ($subgroup instanceof Group) {
            $subgroup = $subgroup->get('code');
        }

        $data = array(
            'code' => $code,
            'name' => $name,
            'description' => $description,
            'subgroup' => $subgroup
        );

        if (self::selectOneByPk($code))
            return false;

        $group = new Group($data);
        $group->insert();

        return $group;
    }

    private function getPermissionList() : array|false {
        $permissions = GroupPermission::select('`group` = :group', array(
            'group' => $this->get('code')
        ));
        if ($permissions == false) {
            return false;
        }
        $subgroup = $this->get('subgroup');
        if ($subgroup != null) {
            $subgroup = Group::selectOneByPk($subgroup);
            if ($subgroup == false) {
                return false;
            }
            $permissions = array_merge($permissions, $subgroup->getPermissionList());
        }
        return GroupPermission::getPermissions($permissions);
    }
        

    public function can(string $permission) : bool {
        $permissions = $this->getPermissionList();
        if ($permissions) {
            foreach ($permissions as $p) {
                if ($p == $permission) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function select(string $where = null, array $params = null) : array|false {
        $result = Database::select(static::getTable(), $where, $params);
        if ($result == false) {
            return false;
        }
        $groups = array();
        foreach ($result as $group) {
            $groups[] = new self($group);
        }
        return $groups;
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
        $groups = array();
        foreach ($result as $group) {
            $groups[] = new self($group);
        }
        return $groups;
    }

    public static function initTable() : string {
        return 'CREATE TABLE IF NOT EXISTS `groups` (
            `code` varchar(255) NOT NULL,
            `name` varchar(255) NOT NULL,
            `description` varchar(255) NOT NULL,
            `subgroup` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`code`)
            )';
    }
}
