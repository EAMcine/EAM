<?php

namespace StandardBundle\Models;

use Framework\Components\Model;
use Framework\Core\Database;

final class Permission extends Model {
    protected static string|null $_pkName = 'code';
    protected static string $_table = 'permissions';

    /**
     * @param string $code
     * @param string $name
     * @param string $description
     */
    public static function create(mixed ...$args) : Permission|false {
        if(count($args) != 3)
            return false;

        $code = $args[0];
        $name = $args[1];
        $description = $args[2];

        $data = array(
            'code' => $code,
            'name' => $name,
            'description' => $description
        );

        if (self::selectOneByPk($code))
            return false;

        $permission = new Permission($data);
        $permission->insert();
        
        return $permission;
    }

    public static function select(string $where = null, array $params = null) : array|false {
        $result = Database::select(static::getTable(), $where, $params);
        if ($result == false) {
            return false;
        }
        $permissions = array();
        foreach ($result as $permission) {
            $permissions[] = new self($permission);
        }
        return $permissions;
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
        $permissions = array();
        foreach ($result as $permission) {
            $permissions[] = new self($permission);
        }
        return $permissions;
    }

    public static function initTable() : string {
        return 'CREATE TABLE IF NOT EXISTS `permissions` (
            `code` varchar(255) NOT NULL,
            `name` varchar(255) NOT NULL,
            `description` varchar(255) NOT NULL,
            PRIMARY KEY (`code`)
            )';
    }
}
