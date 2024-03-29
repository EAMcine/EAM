<?php

namespace Main;

require_once '../src/FrameworkBundle/core/classLoader.php';
use Framework\Core\ClassLoader as ClassLoader;

$loader = new ClassLoader();
$loader->loadFolder('../src/FrameworkBundle/');

use Framework\Routing as Routing;

Main::run();

final class Main {
    private static bool $isRunning = false;

    public static function run() : void {
        if (!self::isRunning()) {
            self::$isRunning = true;
            new self();
        }
    }

    private static function isRunning() : bool {
        return self::$isRunning;
    }

    private function __construct() {

        include_once 'config.php';
        $loader = new ClassLoader();

        if (!file_exists(__DIR__ . '/' . SITE_NAME .  "api.log")) {
            $logfile = fopen(__DIR__ . '/' . SITE_NAME . "api.log", "w");
            fwrite($logfile, 0);
            fclose($logfile);
        }
        
        $logfile = fopen(__DIR__ . '/' . SITE_NAME . "api.log", "r");
        $numberOfConnections = $logfile ? fread($logfile, filesize(__DIR__ . '/' . SITE_NAME . "api.log")) : 0;
        $logfile = fopen(__DIR__ . '/' . SITE_NAME . "api.log", "w");
        fwrite($logfile, $numberOfConnections + 1);
        fclose($logfile);

        // Load all files in src/StandardBundle used in both API and website
        $loader->loadFolder('../src/StandardBundle/');

        $router = Routing\Router::getInstance();
        $routesLoader = Routing\RoutesLoader::getInstance();
        $loader->loadFolder('../src/ApiBundle/');
        $routesLoader->scanRoutesFile('app/routesAPI.yml');

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        set_exception_handler(function($exception) {
            echo json_encode([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        });

        $router->run() ? exit() : $router->reset();

        echo json_encode([
            'status' => 404,
            'message' => 'Not found'
        ]);
    }
}
