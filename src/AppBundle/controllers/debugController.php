<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;
use Framework\Routing\Router as Router;
use Framework\Routing as Routing;

final class DebugController extends Controller {
    use \AppBundle\Traits\DebugTrait;

    public function indexAction() {
        $this->render('Debug');
    }

    public function routesAction() {
        $router = Router::getInstance();
        $router->reset();
        $routesLoader = Routing\RoutesLoader::getInstance();
        $routesLoader->scanRoutesFile('app/routesAPI.yml');
        $routesAPI = $router->getRoutes();
        $router->reset();
        $routesLoader->scanRoutesFile('app/routes.yml');
        $routes = $router->getRoutes();

        $this->render('Routes', [
            'routes' => $routes,
            'routesAPI' => $routesAPI
        ]);
    }

}