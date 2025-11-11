<?php
namespace App\Core;

use PDO;
use App\Core\Config;

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = new PDO("mysql:host=" . Config::get("db_host") . ";dbname=" . Config::get("db_name"), Config::get("db_user"), Config::get("db_pass"));
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}