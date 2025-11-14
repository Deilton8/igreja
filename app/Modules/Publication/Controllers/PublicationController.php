<?php
namespace App\Modules\Publication\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Publication\Models\Publication;
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
        if (!$publicacao) {
            $_SESSION['flash'] = ['error' => 'Publicação não encontrada.'];
            header("Location: /admin/publicacoes");
            exit;
        }
        $title = "Detalhes da Publicação";
        View::render("Publication/Views/admin/show", ["publicacao" => $publicacao, "title" => $title]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $this->publicacaoModel->create($_POST);

                // anexar mídias (se houver)
                if (!empty($_POST['midias'])) {
                    $this->publicacaoModel->attachMedia($id, $_POST['midias']);
                }

                $_SESSION['flash'] = ['success' => 'Publicação criada com sucesso.'];
                header("Location: /admin/publicacoes");
                exit;
            } catch (\Exception $e) {
                $_SESSION['flash'] = ['error' => $e->getMessage()];
            }
        }

        $midias = $this->mediaModel->all();
        $title = "Criar Publicação";
        View::render("Publication/Views/admin/create", ["title" => $title, "midias" => $midias]);
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->publicacaoModel->update($id, $_POST);

                // sincronizar mídias: remove todas e reanexa (simples e seguro)
                $this->publicacaoModel->detachAllMedia($id);
                if (!empty($_POST['midias'])) {
                    $this->publicacaoModel->attachMedia($id, $_POST['midias']);
                }

                $_SESSION['flash'] = ['success' => 'Publicação atualizada com sucesso.'];
                header("Location: /admin/publicacoes");
                exit;
            } catch (\Exception $e) {
                $_SESSION['flash'] = ['error' => $e->getMessage()];
            }
        }

        $publicacao = $this->publicacaoModel->find($id);
        if (!$publicacao) {
            $_SESSION['flash'] = ['error' => 'Publicação não encontrada.'];
            header("Location: /admin/publicacoes");
            exit;
        }

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
        $_SESSION['flash'] = ['success' => 'Publicação removida com sucesso.'];
        header("Location: /admin/publicacoes");
        exit;
    }
}