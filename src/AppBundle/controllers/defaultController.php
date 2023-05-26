<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;
use Framework\Routing\Router as Router;
use Framework\Routing as Routing;

final class defaultController extends Controller {

    public function indexAction() {
        $this->render('index');
    }

    public function showRoutesAction() {
        $router = Router::getInstance();
        $router->reset();
        $routesLoader = Routing\RoutesLoader::getInstance();
        $routesLoader->scanRoutesFile('app/routesAPI.yml');
        $routesAPI = $router->getRoutes();
        $router->reset();
        $routesLoader->scanRoutesFile('app/routes.yml');
        $routes = $router->getRoutes();
        $loader = new \Framework\Core\ClassLoader();
        $loader->loadFile('src/AppBundle/Views/showRoutes.phtml');
        $this->render('showRoutes', [
            'routesAPI' => $routesAPI,
            'routes' => $routes
        ]);
    }

    public function testAction($request) {
        echo 'test ' . $request['id'];
    }

    public function errorAction() {
        $this->error(404, 'Page not found');
    }

}