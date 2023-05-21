<?php

namespace Main;

require_once 'src\FrameworkBundle\core\classLoader.php';
use Framework\Core\ClassLoader as ClassLoader;

$loader = new ClassLoader();
$loader->loadFolder('src/FrameworkBundle/');

use Framework\Routing as Routing;


class Main
{
    public static function main() {

        $router = Routing\Router::getInstance();
        $loader = new ClassLoader();
        $routesLoader = Routing\RoutesLoader::getInstance();
        $loader->loadFolder('src/ApiBundle/');
        $routesLoader->scanRoutesFile('routesAPI.txt');
        $loader->loadFolder('src/AppBundle/');
        $routesLoader->scanRoutesFile('routes.txt');

        $router->run();

    }
}

Main::main();
