<?php

namespace App\Core;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function define($routes)
    {
        $this->routes = $routes;
    }

    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public static function load($file)
    {
        $router = new static;
        require $file;
        return $router;
    }

    public function direct($uri, $requestType)
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            if (is_array($this->routes[$requestType][$uri])) {
                return $this->callAction($this->routes[$requestType][$uri]);
            } else {
                $data = explode('@', $this->routes[$requestType][$uri]);
                $data[0] = "App\\Controllers\\{$data[0]}";
                return $this->callAction($data);
            }
        }
        throw new \Exception('Nu este nicio ruta definita pentru acest URI!');
    }

    protected function callAction($data)
    {
        [$controller, $action] = $data;
        
        $controller = new $controller;
        
        if (!method_exists($controller, $action)) {
            throw new \Exception(
                "{$controller} does not respond to the action {$action} action"
            );
        }

        return $controller->$action();
    }
}
