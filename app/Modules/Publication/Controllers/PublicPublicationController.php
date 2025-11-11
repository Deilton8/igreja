<?php
namespace App\Modules\Publication\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Publication\Models\Publication;

class PublicPublicationController extends Controller
{
    private $publicacaoModel;

    public function __construct()
    {
        $this->publicacaoModel = new Publication();
    }

    // Lista de publicações
    public function index()
    {
        $publicacoesRaw = $this->publicacaoModel->all();
        $publicacoes = [];

        foreach ($publicacoesRaw as $publicacao) {
            $publicacoes[] = $this->publicacaoModel->findWithMedia($publicacao['id']);
        }

        $title = "Publicações - IMGD";
        View::render("Publication/Views/public/index", [
            "publicacoes" => $publicacoes,
            "title" => $title
        ]);
    }

    // Página de detalhe da publicação
    public function show($id)
    {
        $publicacao = $this->publicacaoModel->findWithMedia($id);

        if (!$publicacao) {
            http_response_code(404);
            echo "Publicação não encontrada.";
            return;
        }

        $title = $publicacao["titulo"];

        View::render("Publication/Views/public/show", [
            "publicacao" => $publicacao,
            "title" => $title
        ]);
    }
}
