<?php

namespace Framework\Routing;

final class Route {
    private string $method;
    private string $route;
    private string $controller;
    private string $action;
    
    public function __construct(string $method, string $route, string $controller, string $action) {
        $this->method = $method;
        $this->route = $route;
        $this->controller = $controller;
        $this->action = $action;
    }
    
    public function getMethod() : string {
        return $this->method;
    }

    public function getRoute() : string {
        return $this->route;
    }

    public function getController() : string {
        return $this->controller;
    }

    public function getAction() : string {
        return $this->action;
    }

    public function setMethod($method): void {
        $this->method = $method;
    }

    public function setRoute($route): void {
        $this->route = $route;
    }

    public function setController($controller): void {
        $this->controller = $controller;
    }

    public function setAction($action): void {
        $this->action = $action;
    }
}
