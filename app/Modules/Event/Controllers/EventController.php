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
        $page = $_GET['page'] ?? 1;

        $filters = [
            "search" => $_GET['search'] ?? null,
            "status" => $_GET['status'] ?? null,
            "data_inicio" => $_GET['data_inicio'] ?? null,
            "data_fim" => $_GET['data_fim'] ?? null,
            "local" => $_GET['local'] ?? null,
        ];

        $result = $this->eventModel->list($page, 10, $filters);

        View::render("Event/Views/admin/index", [
            "eventos" => $result['data'],
            "pagination" => $result,
            "filters" => $filters,
            "title" => "Lista de Eventos"
        ]);
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