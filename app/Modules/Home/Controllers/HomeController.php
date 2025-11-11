<?php
namespace App\Modules\Home\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Event\Models\Event;
use App\Modules\Sermon\Models\Sermon;
use App\Modules\Publication\Models\Publication;

class HomeController extends Controller
{
    public function index()
    {
        $eventModel = new Event();
        $sermonModel = new Sermon();
        $publicationModel = new Publication();

        // Eventos com mídias
        $eventosRaw = array_slice($eventModel->all(), 0, 3);
        $eventos = [];
        foreach ($eventosRaw as $evento) {
            $eventos[] = $eventModel->findWithMedia($evento['id']);
        }

        // Sermões com mídias
        $sermoesRaw = array_slice($sermonModel->all(), 0, 3);
        $sermoes = [];
        foreach ($sermoesRaw as $sermao) {
            $sermoes[] = $sermonModel->findWithMedia($sermao['id']);
        }

        // Publicações com mídias
        $publicacoesRaw = array_slice($publicationModel->all(), 0, 3);
        $publicacoes = [];
        foreach ($publicacoesRaw as $pub) {
            $publicacoes[] = $publicationModel->findWithMedia($pub['id']);
        }

        $title = "Página Inicial - IMGD";

        View::render("Home/Views/index", [
            "title" => $title,
            "eventos" => $eventos,
            "sermoes" => $sermoes,
            "publicacoes" => $publicacoes
        ]);
    }
}
