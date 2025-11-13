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
        session_start();
        if (empty($_SESSION["usuario"])) {
            header("Location: /admin/login");
            exit();
        }

        $this->userModel = new User();
    }

    public function index()
    {
        $search = $_GET['q'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 10;

        $pagination = $this->userModel->paginate($page, $perPage, $search, $role, $status);

        $title = "Usuários do Sistema";
        View::render("User/Views/index", [
            'usuarios' => $pagination['data'],
            'pagination' => $pagination,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'title' => $title
        ]);
    }

    public function toggleStatus($id)
    {
        $this->userModel->toggleStatus($id);
        $_SESSION['flash'] = "Status do usuário atualizado com sucesso!";
        header("Location: /admin/usuarios");
        exit;
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->userModel->create($_POST);
                $_SESSION['flash'] = "Usuário criado com sucesso!";
                header("Location: /admin/usuarios");
                exit;
            } catch (\Exception $e) {
                $_SESSION['erro'] = $e->getMessage();
            }
        }

        $title = "Novo usuário";
        View::render("User/Views/create", compact('title'));
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            try {
                $this->userModel->update($id, $_POST);
                $_SESSION['flash'] = "Usuário atualizado com sucesso!";
                header("Location: /admin/usuarios");
                exit;
            } catch (\Exception $e) {
                $_SESSION['erro'] = $e->getMessage();
            }
        }

        $usuario = $this->userModel->find($id);
        $title = "Editar usuário";
        View::render("User/Views/edit", compact('usuario', 'title'));
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        $_SESSION['flash'] = "Usuário removido com sucesso.";
        header("Location: /admin/usuarios");
        exit;
    }

    public function profile($id)
    {
        $usuario = $this->userModel->find($id);
        $title = "Perfil do Usuário " . $usuario['nome'];
        View::render("User/Views/profile", ["usuario" => $usuario, "title" => $title]);
    }
}