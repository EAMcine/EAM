<?php

namespace Framework\Core;

final class ClassLoader {

    public function __construct() {
        
    }

    public function loadFolder(string $folderPath) {
        $files = scandir($folderPath);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $filePath = $folderPath . '/' . $file;
                if (is_dir($filePath)) {
                    $this->loadFolder($filePath);
                } else {
                    $this->loadFile($filePath);
                }
            }
        }
    }

    public function loadFile(string $filePath) {
        require_once $filePath;
    }

}
