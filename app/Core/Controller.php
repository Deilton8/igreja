<?php
namespace App\Core;

class Controller
{
    protected function view($path, $data = [])
    {
        extract($data);
        require __DIR__ . "/../Modules" . $path . ".php";
    }

    protected function model($model)
    {
        $class = "App\\Modules\\" . $model;
        if (class_exists($class)) {
            return new $class();
        }
        throw new \Exception("Model $class não encontrado");
    }
}