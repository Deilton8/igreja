<?php
namespace App\Modules\Media\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Media\Models\Media;

class MediaController extends Controller
{
    private $midiaModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (empty($_SESSION["usuario"])) {
            header("Location: /admin/login");
            exit;
        }

        $this->midiaModel = new Media();
    }

    public function index()
    {
        $filters = [
            'tipo' => $_GET['tipo'] ?? null,
            'q' => $_GET['q'] ?? null
        ];

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $midias = $this->midiaModel->all($filters, $limit, $offset);
        $total = $this->midiaModel->count($filters);
        $totalPages = ceil($total / $limit);

        $title = "Biblioteca de Mídia";

        View::render("Media/Views/index", compact('title', 'midias', 'page', 'totalPages', 'filters'));
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivos'])) {
            $arquivos = $_FILES['arquivos'];
            $uploadDir = "uploads/";

            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0777, true);

            for ($i = 0; $i < count($arquivos['name']); $i++) {
                if ($arquivos['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($arquivos['name'][$i], PATHINFO_EXTENSION);
                    $nomeUnico = uniqid("midia_", true) . "." . $ext;
                    $caminho = $uploadDir . $nomeUnico;

                    if (move_uploaded_file($arquivos['tmp_name'][$i], $caminho)) {
                        $data = [
                            "caminho_arquivo" => $caminho,
                            "nome_arquivo" => $arquivos['name'][$i],
                            "tipo_mime" => $arquivos['type'][$i],
                            "tipo_arquivo" => $this->detectarTipoArquivo($arquivos['type'][$i]),
                            "tamanho" => $arquivos['size'][$i]
                        ];
                        $this->midiaModel->create($data);
                    }
                }
            }

            $_SESSION['flash'] = ['success' => 'Upload realizado com sucesso!'];
            header("Location: /admin/midia");
            exit;
        }
    }

    public function delete($id)
    {
        $midia = $this->midiaModel->find($id);

        if ($midia && file_exists($midia['caminho_arquivo']))
            unlink($midia['caminho_arquivo']);

        $this->midiaModel->delete($id);
        $_SESSION['flash'] = ['success' => 'Mídia excluída com sucesso!'];

        header("Location: /admin/midia");
        exit;
    }

    private function detectarTipoArquivo($mime)
    {
        return match (true) {
            str_contains($mime, "image") => "imagem",
            str_contains($mime, "video") => "video",
            str_contains($mime, "audio") => "audio",
            default => "documento",
        };
    }
}