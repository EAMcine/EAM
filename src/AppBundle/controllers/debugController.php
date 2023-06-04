<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;
use Framework\Routing\Router as Router;
use Framework\Routing as Routing;

final class DebugController extends Controller {
    use \AppBundle\Traits\DebugTrait;

    public function indexAction() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page';
            $this->redirect('/login/');
        }
        $user = $_SESSION['user'];
        if ($user->can('debug') == false) {
            $_SESSION['error'] = 'Vous n\'avez pas les droits pour accéder à cette page';
            $this->redirect('/');
        }
        $this->render('Debug');
    }

    public function phpinfoAction() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page';
            $this->redirect('/login/');
        }
        $user = $_SESSION['user'];
        if ($user->can('debug.phpinfo') == false) {
            $_SESSION['error'] = 'Vous n\'avez pas les droits pour accéder à cette page';
            $this->redirect('/');
        }
        $this->render('PHPInfo');
    }

    public function routesAction() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page';
            $this->redirect('/login/');
        }
        $user = $_SESSION['user'];
        if ($user->can('debug.routes') == false) {
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

    public function logsAction() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page';
            $this->redirect('/login/');
        }
        $user = $_SESSION['user'];
        if ($user->can('debug.logs') == false) {
            $_SESSION['error'] = 'Vous n\'avez pas les droits pour accéder à cette page';
            $this->redirect('/');
        }
        $logs = array();
        $files = scandir(__DIR__ . '../../../../app/logs');
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $logs[] = explode('.', $file)[0];
        }
        $this->render('Logs', [
            'logs' => $logs
        ]);
    }

    public function logAction($request) {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page';
            $this->redirect('/login/');
        }
        $user = $_SESSION['user'];
        if ($user->can('debug.logs') == false) {
            $_SESSION['error'] = 'Vous n\'avez pas les droits pour accéder à cette page';
            $this->redirect('/');
        }
        $file = $request['file'];
        $file = __DIR__ . '../../../../app/logs/' . $file . '.log';
        if (!file_exists($file)) {
            $_SESSION['error'] = 'Le fichier de log n\'existe pas';
            $this->redirect('/debug/logs/');
        }
        $content = file_get_contents($file);
        $this->render('Log', [
                'content' => $content,
                'filename' => $request['file']
            ], 
            'Debug > Logs : ' . $request['file']
        );
    }
}