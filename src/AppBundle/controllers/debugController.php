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
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page';
            $this->redirect('/login/');
        }
        $user = $_SESSION['user'];
        if ($user->can('debug') == false) {
            $_SESSION['error'] = 'Vous n\'avez pas les droits pour accéder à cette page';
            $this->redirect('/');
        }
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