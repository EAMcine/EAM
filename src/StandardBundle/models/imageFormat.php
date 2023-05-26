<?php

namespace StandardBundle\Models;

use Framework\Components\Model;

final class ImageFormat extends Model {
    protected static string|null $_pkName = 'receivedFormat';
    protected static string $_table = 'imageformat';
    
    /**
     * @param string $receivedFormat
     * @param string $outputFormat
     */
    public static function create(mixed ...$args) : ImageFormat|false {
        if (count($args) != 2)
            return false;

        $receivedFormat = $args[0];
        $outputFormat = $args[1];

        $data = [
            'receivedFormat' => $receivedFormat,
            'outputFormat' => $outputFormat
        ];

        return new ImageFormat($data);
    }
}
