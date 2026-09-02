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

    static function run()
    {
        $path = '/';
        if (isset($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
        }
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            if ($route['path'] == $path && $route['method'] == $method) {

                if ($route['middleware'] != []) {
                    $instance = new Middleware();
                    foreach ($route['middleware'] as $middleware) {
                        $instance->$middleware();
                    }
                }

                $controller = $route['controller'];
                $function = $route['function'];

                $instance = new $controller();
                $instance->$function();
                return;
            }
        }

        echo 'Controller not Found';
    }
}

