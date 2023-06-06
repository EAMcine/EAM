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
        $router = Routing\Router::getInstance();
        $routesLoader = Routing\RoutesLoader::getInstance();

        if (!file_exists(__DIR__ . '/' . SITE_NAME . "app.log")) {
            $logfile = fopen(__DIR__ . '/' . SITE_NAME . "app.log", "w");
            fwrite($logfile, 0);
            fclose($logfile);
        }
        
        $logfile = fopen(__DIR__ . '/' . SITE_NAME . "app.log", "r");
        $numberOfConnections = $logfile ? fread($logfile, filesize(__DIR__ . '/' . SITE_NAME . "app.log")) : 0;
        $logfile = fopen(__DIR__ . '/' . SITE_NAME . "app.log", "w");
        fwrite($logfile, $numberOfConnections + 1);
        fclose($logfile);

        // Load all files in src/StandardBundle used in both API and website
        $loader->loadFolder('../src/StandardBundle/');
        session_start();
        ob_start();

        $loader->loadFolder('../src/AppBundle/traits/');
        $loader->loadFolder('../src/AppBundle/');
        $routesLoader->scanRoutesFile('app/routes.yml');

        header('Content-Type: text/html');

        set_exception_handler(function($exception) {
            echo '<h1>Exception</h1>';
            echo $exception->getMessage();
            echo '<br>';
            echo '<pre>';
            echo $exception->getTraceAsString();
            echo '</pre>';
        });

        $router->run();
    }
}
