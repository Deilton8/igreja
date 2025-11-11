<?php
namespace App\Modules\Event\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Event\Models\Event;
use App\Modules\Media\Models\Media;

class EventController extends Controller
{
    private $eventModel;
    private $mediaModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["usuario"])) {
            header("Location: /admin/login");
            exit();
        }

        $this->eventModel = new Event();
        $this->mediaModel = new Media();
    }

    public function index()
    {
        $eventos = $this->eventModel->all();
        $title = "Lista de Eventos";
        View::render("Event/Views/admin/index", ["eventos" => $eventos, "title" => $title]);
    }

    public function show($id)
    {
        $evento = $this->eventModel->findWithMedia($id);
        $title = "Detalhes do evento";
        View::render("Event/Views/admin/show", ["evento" => $evento, "title" => $title]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = $this->eventModel->create($_POST);

            if (!empty($_POST['midias'])) {
                $this->eventModel->attachMedia($eventId, $_POST['midias']);
            }

            header("Location: /admin/eventos");
            exit;
        }

        $midias = $this->mediaModel->all();
        $title = "Criar Evento";
        View::render("Event/Views/admin/create", ["title" => $title, "midias" => $midias]);
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->eventModel->update($id, $_POST);

            // atualizar mídias vinculadas
            if (!empty($_POST['midias'])) {
                $this->eventModel->attachMedia($id, $_POST['midias']);
            }

            header("Location: /admin/eventos");
            exit;
        }

        $evento = $this->eventModel->find($id);
        $midias = $this->mediaModel->all();
        $midiasEvento = $this->eventModel->getMedia($id);
        $title = "Editar Evento";

        View::render("Event/Views/admin/edit", [
            "evento" => $evento,
            "midias" => $midias,
            "midiasEvento" => array_column($midiasEvento, 'id'),
            "title" => $title
        ]);
    }

    public function delete($id)
    {
        $this->eventModel->delete($id);
        header("Location: /admin/eventos");
        exit;
    }
}