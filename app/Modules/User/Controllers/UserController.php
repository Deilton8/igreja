<?php
namespace App\Modules\User\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\User\Models\User;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        if (session_start() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["usuario"])) {
            header("Location: /admin/login");
            exit();
        }

        $this->userModel = new User();
    }

    public function index()
    {
        $usuarios = $this->userModel->all();
        $title = "Lista de usuários";
        View::render("User/Views/index", ["usuarios" => $usuarios, "title" => $title]);
    }

    public function profile($id)
    {
        $usuario = $this->userModel->find($id);
        $title = "Perfil de " . $usuario['nome'];
        View::render("User/Views/profile", ["usuario" => $usuario, "title" => $title]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->create($_POST);
            header("Location: /admin/usuarios");
            exit;
        }
        $title = "Novo usuário";
        View::render("User/Views/create", ["title" => $title]);
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $this->userModel->update($id, $_POST);
            header("Location: /admin/usuarios");
            exit;
        }
        $usuario = $this->userModel->find($id);
        $title = "Editar usuário";
        View::render("User/Views/edit", ["usuario" => $usuario, "title" => $title]);
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        header("Location: /admin/usuarios");
        exit;
    }
}