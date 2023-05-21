<?php

namespace Framework\Routing;

use Framework\Routing\Route as Route;

class Router {

  private static Router|null $instance = null;
  private static array $routes = [];
  
  private function __construct() { 
    self::$routes = [];
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new Router();
    }
    return self::$instance;
  }

  public static function getRoutes() {
    return self::$routes;
  }

  public static function addRoute(Route $route) {
    if ($route instanceof Route) {
      array_push(self::$routes, $route);
    }
  }

  public static function addRoutes(array $routes) {
    foreach ($routes as $route) {
      self::addRoute($route);
    }
  }

  public static function run() {
    $routes = self::$routes;
    $route_found = false;
    foreach ($routes as $tested_route) {
      $method = $tested_route->getMethod();
      $route = $tested_route->getRoute();
      $controller = $tested_route->getController();
      $action = $tested_route->getAction();
      $route_found = self::$method($route, $controller, $action);
      if ($route_found) {
        return true;
      }
    }
    return false;
  }

  public static function reset() {
    self::$routes = [];
  }

  private static function get(string $route, string $controller, string $action) {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      return self::route($route, $controller, $action);
    }
  }

  private static function post(string $route, string $controller, string $action) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      return self::route($route, $controller, $action);
    }
  }

  private static function put(string $route, string $controller, string $action) {
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
      return self::route($route, $controller, $action);
    }
  }

  private static function patch(string $route, string $controller, string $action) {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
      return self::route($route, $controller, $action);
    }
  }

  private static function delete(string $route, string $controller, string $action) {
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
      return self::route($route, $controller, $action);
    }
  }

  private static function any(string $route, string $controller, string $action) {
    return self::route($route, $controller, $action);
  }

  private static function route(string $route, string $controller, string $action) : bool {
    // $route is a string like '/user/{id}/edit'
    // $route is a string like '/user/1/edit'
    // $controller is a string like 'AppBundle\controllers\userController'
    // $action is a string like 'editAction' but the method can take parameters  
    if ($route == '/404/') {
      $controller = 'AppBundle\\Controllers\\defaultController';
      $action = 'errorAction';
      $controller = new $controller();
      $controller->$action();
      return true;
    }
    
    $route = explode('/', $route);
    $request = explode('/', $_SERVER['REQUEST_URI']);
    $params = [];
    if (count($route) != count($request)) {
      return false;
    }
    for ($i = 0; $i < count($route); $i++) {
      if ($route[$i] != $request[$i]) {
        if (strpos($route[$i], '{') !== false && strpos($route[$i], '}') !== false) {
          $param = str_replace('{', '', $route[$i]);
          $param = str_replace('}', '', $param);
          $params[$param] = $request[$i];
        } else {
          return false;
        }
      }
    }
    $controller = new $controller();
    $controller->$action($params);
    return true;
  }
}
