<?php
namespace App\Modules\Sermon\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Sermon\Models\Sermon;

class PublicSermonController extends Controller
{
    private $sermonModel;

    public function __construct()
    {
        $this->sermonModel = new Sermon();
    }

    public function index()
    {
        $sermoesRaw = $this->sermonModel->all();
        $sermoes = [];

        foreach ($sermoesRaw as $sermon) {
            $sermoes[] = $this->sermonModel->findWithMedia($sermon['id']);
        }

        $title = "Sermões";
        View::render("Sermon/Views/public/index", [
            "sermoes" => $sermoes,
            "title" => $title
        ]);
    }

    public function show($id)
    {
        $sermao = $this->sermonModel->findWithMedia($id);
        if (!$sermao) {
            http_response_code(404);
            echo "Sermão não encontrado.";
            return;
        }

        $title = $sermao["titulo"];
        View::render("Sermon/Views/public/show", [
            "sermao" => $sermao,
            "title" => $title
        ]);
    }
}