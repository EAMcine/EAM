<?php

namespace Main;

require_once 'src\FrameworkBundle\core\classLoader.php';
use Framework\Core\ClassLoader as ClassLoader;

$loader = new ClassLoader();
$loader->loadFolder('src/FrameworkBundle/');

use Framework\Routing as Routing;
use \tidy as tidy;

Main::run();

class Main {
    private static bool $isRunning = false;

    public static function run() {
        if (!self::isRunning()) {
            self::main();
        }
    }

    private static function isRunning() {
        return self::$isRunning;
    }

    private static function main() {

        include_once 'app/config.php';

        $router = Routing\Router::getInstance();
        $loader = new ClassLoader();
        $routesLoader = Routing\RoutesLoader::getInstance();
        $loader->loadFolder('src/ApiBundle/');
        $routesLoader->scanRoutesFile('app/routesAPI.yml');

        header('Content-Type: application/json');

        set_exception_handler(function($exception) {
            echo json_encode([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        });

        $router->run() ? exit() : $router->reset();

        session_start();
        ob_start();

        $loader->loadFolder('src/AppBundle/');
        $routesLoader->scanRoutesFile('app/routes.yml');

        header('Content-Type: text/html');

        set_exception_handler(function($exception) {
            echo $exception->getMessage();
        });

        $router->run();

        $output = ob_get_clean();
        $tidyConfig = array(
          'indent' => true,
          'indent-spaces' => 4,
          'wrap' => 0,
          'output-xhtml' => true,
          'show-errors' => 0,
        );
        $tidy = new tidy();
        $tidy->parseString($output, $tidyConfig);
        $tidy->cleanRepair();
        echo $tidy;
    }
}
