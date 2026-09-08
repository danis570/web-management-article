<?php

namespace app\App;

use app\Middleware\Middleware;

class Router
{
    static array $routes = [];

    static function add(
        string $method,
        string $path,
        string $controller,
        string $function,
        array $middleware = []
    ) {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'function' => $function,
            'middleware' => $middleware
        ];
    }

    public static function run(): void
    {
        $path = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            // Method HTTP harus cocok
            if ($route['method'] !== $method) {
                continue;
            }

            $routePath = $route['path'];
            $params = [];

            // 1. KONDISI: Route Sama Persis (Tanpa Parameter)
            if ($routePath === $path) {
                self::executeRoute($route, $params);
                return;
            }

            // 2. KONDISI: Route Dinamis (Dengan Parameter)
            $routeParts = explode('/', trim($routePath, '/'));
            $pathParts = explode('/', trim($path, '/'));

            // Jumlah segmen URL harus sama
            if (count($routeParts) !== count($pathParts)) {
                continue;
            }

            $match = true;
            foreach ($routeParts as $index => $routePart) {
                if (str_starts_with($routePart, '{') && str_ends_with($routePart, '}')) {
                    $paramName = trim($routePart, '{}');
                    $params[$paramName] = $pathParts[$index];
                } elseif ($routePart !== $pathParts[$index]) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                self::executeRoute($route, $params);
                return;
            }
        }

        // Jika tidak ada route yang cocok
        http_response_code(404);
        echo 'Controller not Found';
    }

    /**
     * Helper untuk mengeksekusi middleware dan controller
     */
    private static function executeRoute(array $route, array $params): void
    {
        // Jalankan Middleware jika ada
        if (!empty($route['middleware'])) {
            $middlewareInstance = new Middleware();
            foreach ($route['middleware'] as $middleware) {
                $middlewareInstance->$middleware();
            }
        }

        // Jalankan Controller
        $controller = $route['controller'];
        $function = $route['function'];

        $controllerInstance = new $controller();
        $controllerInstance->$function($params);
    }

}

