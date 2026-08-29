<?php

namespace app\App;

class Router
{
    static array $routes = [];

    static function add(
        string $method,
        string $path,
        string $controller,
        string $function
    ) {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'function' => $function
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

