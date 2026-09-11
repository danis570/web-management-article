<?php

namespace app\App;

class Router
{
    private static array $routes = [];

    public static function add(
        string $method,
        string $path,
        string $controller,
        string $function,
        array $middleware = []
    ): void {
        self::$routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'function' => $function,
            'middleware' => $middleware
        ];
    }

    public static function run(): void
    {
        $path = $_SERVER['PATH_INFO'] ?? '/';
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        foreach (self::$routes as $route) {

            // HTTP method harus cocok
            if ($route['method'] !== $method) {
                continue;
            }

            $params = [];

            // Route statis
            if ($route['path'] === $path) {
                self::executeRoute($route, $params);
                return;
            }

            // Route dinamis
            $routeParts = explode(
                '/',
                trim($route['path'], '/')
            );

            $pathParts = explode(
                '/',
                trim($path, '/')
            );

            // Jumlah segment harus sama
            if (count($routeParts) !== count($pathParts)) {
                continue;
            }

            $match = true;

            foreach ($routeParts as $index => $routePart) {

                // Parameter
                if (
                    str_starts_with($routePart, '{') &&
                    str_ends_with($routePart, '}')
                ) {
                    $paramName = trim($routePart, '{}');

                    $params[$paramName] = $pathParts[$index];

                    continue;
                }

                // Segment tidak cocok
                if ($routePart !== $pathParts[$index]) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                self::executeRoute($route, $params);
                return;
            }
        }

        http_response_code(404);

        echo 'Controller not Found';
    }

    private static function executeRoute(
        array $route,
        array $params
    ): void {

        // =========================
        // MIDDLEWARE
        // =========================

        foreach ($route['middleware'] as $middleware) {

            $middlewareInstance = new $middleware();

            $middlewareInstance->handle();
        }

        // =========================
        // CONTROLLER
        // =========================

        $controller = $route['controller'];
        $function = $route['function'];

        $controllerInstance = new $controller();

        $controllerInstance->$function($params);
    }
}
