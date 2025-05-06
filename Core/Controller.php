<?php

namespace Core;

class Controller {
    protected function loadModel($model) {
        $modelClass = "App\\Models\\$model";
        return new $modelClass();
    }

    protected function loadView($view, $data = []) {
        $viewFile = "../App/Views/$view.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            die("View $view not found!");
        }
    }
}