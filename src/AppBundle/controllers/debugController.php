<?php

namespace AppBundle\Controllers;

use Framework\Components\Controller as Controller;
use Framework\Routing\Router as Router;
use Framework\Routing as Routing;

final class DebugController extends Controller {
    use \AppBundle\Traits\DebugTrait;

    public function indexAction() {
        $this->canDebug();
        $this->render('Debug');
    }

    public function phpinfoAction() {
        $this->canDebug('phpinfo');
        $this->render('PHPInfo');
    }

    public function routesAction() {
        $this->canDebug('routes');
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
        $this->canDebug('logs');
        $logs = array();
        if (!file_exists(__DIR__ . '../../../../app/logs')) {
            mkdir(__DIR__ . '../../../../app/logs', 0777, true);
        }
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
        $this->canDebug('logs');
        $file = $request['file'];
        $file = __DIR__ . '../../../../app/logs/' . $file . '.log';
        if (!file_exists($file)) {
            $_SESSION['error'] = 'Le fichier de log n\'existe pas';
            $this->redirect('/debug/logs');
        }
        $content = file_get_contents($file);
        $this->render('Log', [
                'content' => $content,
                'filename' => $request['file']
            ], 
            'Debug > Log : ' . $request['file']
        );
    }
}