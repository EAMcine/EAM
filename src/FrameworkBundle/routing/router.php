<?php

namespace Framework\Routing;

use Framework\Routing\Route as Route;

class Router {

  private static Router|null $instance = null;
  private static array $routes = [];
  
  private function __construct() { 
    self::$routes = [];
  }

  public static function getInstance() : Router {
    if (self::$instance == null) {
      self::$instance = new Router();
    }
    return self::$instance;
  }

  public static function getRoutes() : array {
    return self::$routes;
  }

  public static function addRoute(Route $route) : void {
    if ($route instanceof Route) {
      array_push(self::$routes, $route);
    }
  }

  public static function addRoutes(array $routes) : void {
    foreach ($routes as $route) {
      self::addRoute($route);
    }
  }

  public static function run() : bool {
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

  public static function reset() : void {
    self::$routes = [];
  }

  private static function get(string $route, string $controller, string $action) : bool {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      return self::route($route, $controller, $action) ?? false;
    }
    return false;
  }

  private static function post(string $route, string $controller, string $action) : bool {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      return self::route($route, $controller, $action) ?? false;
    }
    return false;
  }

  private static function put(string $route, string $controller, string $action) : bool {
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
      return self::route($route, $controller, $action);
    }
    return false;
  }

  private static function patch(string $route, string $controller, string $action) : bool {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
      return self::route($route, $controller, $action);
    }
    return false;
  }

  private static function delete(string $route, string $controller, string $action) : bool {
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
      return self::route($route, $controller, $action);
    }
    return false;
  }

  private static function any(string $route, string $controller, string $action) : bool {
    return self::route($route, $controller, $action);
  }

  private static function route(string $route, string $controller, string $action) : bool {

    if ($route == '/404/') {
      $controller = new $controller();
      $controller->$action();
      return true;
    }

    if (substr($route, -1) == '/') {
      $route = substr($route, 0, -1);
    }
    $route = explode('/', $route);
    $request = $_SERVER['REQUEST_URI'];
    if (substr($request, -1) == '/') {
      $request = substr($request, 0, -1);
    }
    $request = explode('/', $request);
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
    try {
      $controller = new $controller();
      $controller->$action($params);
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }
}
