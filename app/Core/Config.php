<?php
namespace App\Core;

class Config
{
    public static function get($key)
    {
        $settings = [
            "db_host" => "localhost",
            "db_name" => "igreja",
            "db_user" => "root",
            "db_pass" => ""
        ];
        return $settings[$key] ?? null;
    }
}