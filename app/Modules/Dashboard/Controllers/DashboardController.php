<?php
namespace App\Modules\Dashboard\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Dashboard\Models\Dashboard;

class DashboardController extends Controller
{
    protected $dashboardModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["usuario"])) {
            header("Location: /admin/login");
            exit;
        }

        $this->dashboardModel = new Dashboard();
    }

    public function index()
    {
        $title = "Dashboard";
        $usuario = $_SESSION["usuario"];

        $estatisticas = $this->dashboardModel->getEstatisticas();
        $eventos = $this->dashboardModel->getProximosEventos();
        $publicacoes = $this->dashboardModel->getUltimasPublicacoes();
        $sermoes = $this->dashboardModel->getUltimosSermoes();
        $eventosPorStatus = $this->dashboardModel->getEventosPorStatus();
        $publicacoesPorCategoria = $this->dashboardModel->getPublicacoesPorCategoria();

        View::render("Dashboard/Views/index", [
            "usuario" => $usuario,
            "title" => $title,
            "estatisticas" => $estatisticas,
            "eventos" => $eventos,
            "publicacoes" => $publicacoes,
            "sermoes" => $sermoes,
            "eventosPorStatus" => $eventosPorStatus,
            "publicacoesPorCategoria" => $publicacoesPorCategoria
        ]);
    }
}