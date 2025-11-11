<?php
namespace App\Modules\Publication\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Publication\Models\Publication;;
use App\Modules\Media\Models\Media;

class PublicationController extends Controller
{
    private $publicacaoModel;
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

        $this->publicacaoModel = new Publication();
        $this->mediaModel = new Media();
    }

    public function index()
    {
        $publicacoes = $this->publicacaoModel->all();
        $title = "Lista de Publicações";
        View::render("Publication/Views/admin/index", ["publicacoes" => $publicacoes, "title" => $title]);
    }

    public function show($id)
    {
        $publicacao = $this->publicacaoModel->findWithMedia($id);
        $title = "Detalhes da Publicação";
        View::render("Publication/Views/admin/show", ["publicacao" => $publicacao, "title" => $title]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->publicacaoModel->create($_POST);

            if (!empty($_POST['midias'])) {
                $this->publicacaoModel->attachMedia($id, $_POST['midias']);
            }

            header("Location: /admin/publicacoes");
            exit;
        }

        $midias = $this->mediaModel->all();
        $title = "Criar Publicação";
        View::render("Publication/Views/admin/create", ["title" => $title, "midias" => $midias]);
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->publicacaoModel->update($id, $_POST);

            if (!empty($_POST['midias'])) {
                $this->publicacaoModel->attachMedia($id, $_POST['midias']);
            }

            header("Location: /admin/publicacoes");
            exit;
        }

        $publicacao = $this->publicacaoModel->find($id);
        $midias = $this->mediaModel->all();
        $midiasPublicacao = $this->publicacaoModel->getMedia($id);
        $title = "Editar Publicação";

        View::render("Publication/Views/admin/edit", [
            "publicacao" => $publicacao,
            "midias" => $midias,
            "midiasPublicacao" => array_column($midiasPublicacao, 'id'),
            "title" => $title
        ]);
    }

    public function delete($id)
    {
        $this->publicacaoModel->delete($id);
        header("Location: /admin/publicacoes");
        exit;
    }
}