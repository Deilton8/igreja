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
        if (session_start() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["usuario"])) {
            header("Location: /admin/login");
            exit;
        }

        $this->midiaModel = new Media();
    }

    public function index()
    {
        $midias = $this->midiaModel->all();
        $title = "Biblioteca de Mídias";
        View::render("Media/Views/index", ["title" => $title, "midias" => $midias]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivos'])) {
            $arquivos = $_FILES['arquivos'];
            $uploadDir = "uploads/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // percorre todos os arquivos enviados
            for ($i = 0; $i < count($arquivos['name']); $i++) {
                if ($arquivos['error'][$i] === UPLOAD_ERR_OK) {
                    $nomeArquivo = basename($arquivos['name'][$i]);
                    $tmp = $arquivos['tmp_name'][$i];
                    $caminho = $uploadDir . $nomeArquivo;

                    if (move_uploaded_file($tmp, $caminho)) {
                        $data = [
                            "caminho_arquivo" => $caminho,
                            "nome_arquivo" => $nomeArquivo,
                            "tipo_mime" => $arquivos['type'][$i],
                            "tipo_arquivo" => $this->detectarTipoArquivo($arquivos['type'][$i]),
                            "tamanho" => $arquivos['size'][$i]
                        ];
                        $this->midiaModel->create($data);
                    }
                }
            }

            header("Location: /admin/midia");
            exit;
        }
    }

    public function delete($id)
    {
        $midia = $this->midiaModel->find($id);

        if ($midia && file_exists($midia['caminho_arquivo'])) {
            unlink($midia['caminho_arquivo']); // remover arquivo físico
        }

        $this->midiaModel->delete($id);
        header("Location: /admin/midia");
        exit;
    }

    private function detectarTipoArquivo($mime)
    {
        if (str_contains($mime, "image"))
            return "imagem";
        if (str_contains($mime, "video"))
            return "video";
        if (str_contains($mime, "audio"))
            return "audio";
        return "documento";
    }
}