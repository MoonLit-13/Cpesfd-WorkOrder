<?php

namespace Core;

class App {
    public function __construct() {
        $url = $this->parseUrl();
         $controllerName = $url[0] ?? 'WorkOrderController';
        $methodName = $url[1] ?? 'index';
        $params = array_slice($url, 2);

        $controllerClass = "App\\Controllers\\$controllerName";
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $methodName)) {
                call_user_func_array([$controller, $methodName], $params);
            } else {
                die("Method $methodName not found!");
            }
        } else {
            die("Controller $controllerName not found!");
        }
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}