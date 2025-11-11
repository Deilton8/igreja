<?php
namespace App\Core;

class View
{
    public static function render($view, $data = [])
    {
        extract($data);
        $file = __DIR__ . "/../Modules/" . $view . ".php";
        if (file_exists($file)) {
            require $file;
        } else {
            echo "View {$view} não encontrada.";
        }
    }
}