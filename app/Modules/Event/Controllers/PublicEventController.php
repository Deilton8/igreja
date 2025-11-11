<?php
namespace App\Modules\Event\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Event\Models\Event;

class PublicEventController extends Controller
{
    private $eventModel;

    public function __construct()
    {
        $this->eventModel = new Event();
    }

    // Lista de eventos públicos
    public function index()
    {
        $eventosRaw = $this->eventModel->all();
        $eventos = [];

        foreach ($eventosRaw as $evento) {
            $eventos[] = $this->eventModel->findWithMedia($evento['id']);
        }

        $title = "Eventos - IMGD";
        View::render("Event/Views/public/index", [
            "eventos" => $eventos,
            "title" => $title
        ]);
    }

    // Página de evento individual
    public function show($id)
    {
        $evento = $this->eventModel->findWithMedia($id);

        if (!$evento) {
            http_response_code(404);
            echo "Evento não encontrado.";
            return;
        }

        $title = $evento["titulo"];
        View::render("Event/Views/public/show", [
            "evento" => $evento,
            "title" => $title
        ]);
    }
}