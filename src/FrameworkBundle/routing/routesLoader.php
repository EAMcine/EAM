<?php

namespace Framework\Routing;

use Framework\Routing\Route as Route;

class RoutesLoader {
    private static RoutesLoader|null $instance = null;

    private function __construct() { 
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new RoutesLoader();
        }
        return self::$instance;
    }

    public static function scanRoutesFile(string $routes_file) {
        $file = fopen(__DIR__ . '/../../../' . $routes_file , 'r');
        $routes = [];
        while (!feof($file)) {
            $line = fgets($file);
            $line = trim($line);
            if ($line != '') {
                $line = explode('|', $line);
                $method = trim($line[0]);
                $route = trim($line[1]);
                $controller = trim($line[2]);
                $action = trim($line[3]);
                $route = new Route($method, $route, $controller, $action);
                array_push($routes, $route);
            }
        }
        Router::addRoutes($routes);
        fclose($file);
    }
}
