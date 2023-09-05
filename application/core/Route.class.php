<?php
// роутер сайта

class Route
{
    public static function Start()
    {
        $controller = 'index';
        $uri = preg_replace("/\?.*/i", '', $_SERVER['REQUEST_URI']);

        if (mb_substr($uri, -1) !== "/") {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: $uri/");
            exit();
        }

        $route_array = explode('/', $uri);
        array_shift($route_array);
        array_pop($route_array);

        if (!empty($route_array[0])) {
            $controller = $route_array[0];
        }

        $controller_name = 'Controller_' . $controller;

        if (class_exists($controller_name)) {
            $controller = new $controller_name();
            $controller->actionCheck($route_array);
        }

        static::get404();
    }

    private static function get404()
    {
        $controller = new Controller();
        $controller->action_404();
    }
}
