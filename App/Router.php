<?php

namespace App;

class Router
{
    protected array $routes = [];

    public function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[strtoupper($method)][$path] = $handler;
    }

    public function match(string $method, string $uri): array|false
    {
        $method = strtoupper($method);

        if (isset($this->routes[$method]) && isset($this->routes[$method][$uri])) {
            return [
                'handler' => $this->routes[$method][$uri],
                'vars' => [] 
            ];
        }

        foreach ($this->routes[$method] as $routePath => $handler) {
            $pattern = str_replace('/', '\/', $routePath);
            $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $pattern);

            if (preg_match('/^' . $pattern . '$/', $uri, $matches)) {
                array_shift($matches);

                $vars = [];
                preg_match_all('/\{(\w+)\}/', $routePath, $paramNames);

                if (!empty($paramNames[1])) {
                    foreach ($paramNames[1] as $index => $paramName) {
                        if (isset($matches[$index])) {
                            $vars[$paramName] = $matches[$index];
                        }
                    }
                }

                return [
                    'handler' => $handler,
                    'vars' => $vars
                ];
            }
        }

        return false;
    }
}
