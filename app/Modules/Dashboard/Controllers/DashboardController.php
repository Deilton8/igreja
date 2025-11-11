<?php
namespace App\Modules\Dashboard\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Dashboard\Models\Dashboard;

class DashboardController extends Controller
{
    public function __construct()
    {
        if (session_start() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["usuario"])) {
            header("Location: /admin/login");
            exit;
        }
    }

    public function index()
    {
        $title = "Dashboard";
        $usuario = $_SESSION["usuario"];
        View::render("Dashboard/Views/index", ["usuario" => $usuario, "title" => $title]);
    }
}