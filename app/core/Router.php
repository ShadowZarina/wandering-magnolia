<?php

class Router {
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void {
        $this->routes['GET'][$path] = [$controller, $method];
    }

    public function post(string $path, string $controller, string $method): void {
        $this->routes['POST'][$path] = [$controller, $method];
    }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = strtok($_SERVER['REQUEST_URI'], '?');
        // strip subfolder if needed
        $base   = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $path   = '/' . ltrim(substr($uri, strlen($base)), '/');
        $path   = $path === '' ? '/' : $path;

        if (isset($this->routes[$method][$path])) {
            [$controllerName, $action] = $this->routes[$method][$path];
            require_once ROOT . "/app/controllers/{$controllerName}.php";
            $controller = new $controllerName();
            $controller->$action();
        } else {
            http_response_code(404);
            echo '<h1>404 — Page Not Found</h1>';
        }
    }
}
