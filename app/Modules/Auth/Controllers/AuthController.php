<?php
namespace App\Modules\Auth\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Modules\Auth\Models\Auth;

class AuthController extends Controller
{
    private $authModel;

    public function __construct()
    {
        if (session_start() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->authModel = new Auth();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $usuario = $this->authModel->attempt($email, $senha);

            if ($usuario) {
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                    'papel' => $usuario['papel'],
                    'status' => $usuario['status'],
                ];
                header("Location: /admin");
                exit;
            } else {
                $error = "Email ou senha inválidos";
                View::render("Auth/Views/login", ["error" => $error]);
                return;
            }
        }
        View::render("Auth/Views/login");
    }

    public function logout()
    {
        session_destroy();
        header("Location: /admin/login");
        exit;
    }
}