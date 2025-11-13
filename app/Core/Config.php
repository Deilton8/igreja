<?php
namespace App\Core;

class Config
{
    public static function get($key)
    {
        $settings = [
            "db_host" => "localhost",
            "db_name" => "imgdorgm_igreja",
            "db_user" => "imgdorgm_imgd",
            "db_pass" => "Ministerio@2007"
        ];
        return $settings[$key] ?? null;
    }
}