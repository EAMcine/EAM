<?php

namespace StandardBundle\Models;

use Framework\Components\Model;
use Framework\Core\Database;

final class ImageFormat extends Model {
    protected static string|null $_pkName = 'receivedFormat';
    protected static string $_table = 'imageformats';
    
    /**
     * @param string $receivedformat
     * @param string $localformat
     */
    public static function create(mixed ...$args) : ImageFormat|false {
        if (count($args) != 2)
            return false;

        $receivedFormat = $args[0];
        $outputFormat = $args[1];

        $data = [
            'receivedformat' => $receivedFormat,
            'localformat' => $outputFormat
        ];

        if (self::selectOneByPk($receivedFormat))
            return false;

        $imageFormat = new ImageFormat($data);
        $imageFormat->insert();

        return $imageFormat;
    }

    public static function select(string $where = null, array $params = null) : array|false {
        $result = Database::select(static::getTable(), $where, $params);
        if ($result == false) {
            return false;
        }
        $imageFormats = array();
        foreach ($result as $imageFormat) {
            $imageFormats[] = new self($imageFormat);
        }
        return $imageFormats;
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
        $imageFormats = array();
        foreach ($result as $imageFormat) {
            $imageFormats[] = new self($imageFormat);
        }
        return $imageFormats;
    }

    public static function initTable() : string {
        return 'CREATE TABLE IF NOT EXISTS `imageformats` (
            `receivedformat` varchar(255) NOT NULL,
            `localformat` varchar(255) NOT NULL,
            PRIMARY KEY (`receivedformat`) 
            )';
    }

}
