<?php

namespace Main;

require_once 'src\FrameworkBundle\core\classLoader.php';
use Framework\Core\ClassLoader as ClassLoader;

$loader = new ClassLoader();
$loader->loadFolder('src/FrameworkBundle/');

use Framework\Routing as Routing;

Main::main();

class Main {
    public static function main() {

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

        $loader->loadFolder('src/AppBundle/');
        $routesLoader->scanRoutesFile('app/routes.yml');

        header('Content-Type: text/html');

        set_exception_handler(function($exception) {
            echo $exception->getMessage();
        });

        $router->run();

    }
}
