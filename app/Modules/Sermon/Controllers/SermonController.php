<?php
namespace App\Modules\Sermon\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Sermon\Models\Sermon;
use App\Modules\Media\Models\Media;

class SermonController extends Controller
{
    private $sermonModel;
    private $mediaModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (empty($_SESSION["usuario"])) {
            header("Location: /admin/login");
            exit();
        }

        $this->sermonModel = new Sermon();
        $this->mediaModel = new Media();
    }

    public function index()
    {
        $sermoes = $this->sermonModel->all();
        $title = "Lista de Sermões";
        View::render("Sermon/Views/admin/index", ["sermoes" => $sermoes, "title" => $title]);
    }

    public function show($id)
    {
        $sermao = $this->sermonModel->findWithMedia($id);
        $title = "Detalhes do Sermão";
        View::render("Sermon/Views/admin/show", ["sermao" => $sermao, "title" => $title]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sermaoId = $this->sermonModel->create($_POST);

            if (!empty($_POST['midias'])) {
                $this->sermonModel->attachMedia($sermaoId, $_POST['midias']);
            }

            header("Location: /admin/sermoes");
            exit;
        }

        $midias = $this->mediaModel->all();
        $title = "Criar Sermão";
        View::render("Sermon/Views/admin/create", ["title" => $title, "midias" => $midias]);
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->sermonModel->update($id, $_POST);

            if (!empty($_POST['midias'])) {
                $this->sermonModel->attachMedia($id, $_POST['midias']);
            }

            header("Location: /admin/sermoes");
            exit;
        }

        $sermao = $this->sermonModel->find($id);
        $midias = $this->mediaModel->all();
        $midiasSermao = $this->sermonModel->getMedia($id);
        $title = "Editar Sermão";

        View::render("Sermon/Views/admin/edit", [
            "sermao" => $sermao,
            "midias" => $midias,
            "midiasSermao" => array_column($midiasSermao, 'id'),
            "title" => $title
        ]);
    }

    public function delete($id)
    {
        $this->sermonModel->delete($id);
        header("Location: /admin/sermoes");
        exit;
    }
}