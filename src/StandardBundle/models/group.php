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
        $code = $args[0];
        $name = $args[1];
        $description = $args[2];
        $subgroup = $args[3] ?? null;

        $data = array(
            'code' => $code,
            'name' => $name,
            'description' => $description,
            'subgroup' => $subgroup
        );

        return new Group($data);
    }

    public static function initTable() : string {
        return 'CREATE TABLE IF NOT EXISTS `groups` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `description` varchar(255) NOT NULL,
            `subgroup` int(11) NOT NULL,
            PRIMARY KEY (`id`)
            )';
    }
}