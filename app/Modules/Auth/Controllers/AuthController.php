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
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $this->authModel = new Auth();

        // Auto login via cookie
        if (!isset($_SESSION['usuario']) && isset($_COOKIE['remember_token'])) {
            $usuario = $this->authModel->autenticarPorToken($_COOKIE['remember_token']);
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
            }
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            $lembrar = isset($_POST['lembrar']);

            $resultado = $this->authModel->attempt($email, $senha);

            if (isset($resultado['error'])) {
                View::render("Auth/Views/login", ["error" => $resultado['error']]);
                return;
            }

            $_SESSION['usuario'] = [
                'id' => $resultado['id'],
                'nome' => $resultado['nome'],
                'email' => $resultado['email'],
                'papel' => $resultado['papel'],
                'status' => $resultado['status'],
            ];

            if ($lembrar) {
                $this->authModel->gerarTokenLogin($resultado['id']);
            }

            header("Location: /admin");
            exit;
        }

        View::render("Auth/Views/login");
    }

    public function logout()
    {
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        session_destroy();
        header("Location: /admin/login");
        exit;
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $token = $this->authModel->gerarTokenRecuperacao($email);

            if ($token) {
                $link = "http://" . $_SERVER['HTTP_HOST'] . "/admin/resetar-senha?token=" . $token;

                // Em um sistema real, você enviaria por e-mail.
                // Aqui, apenas mostramos o link na tela para testes:
                View::render("Auth/Views/forgot", [
                    "success" => "Um link de redefinição foi gerado (veja abaixo).",
                    "link" => $link
                ]);
                return;
            } else {
                View::render("Auth/Views/forgot", [
                    "error" => "E-mail não encontrado no sistema."
                ]);
                return;
            }
        }

        View::render("Auth/Views/forgot");
    }

    public function resetPassword()
    {
        $token = $_GET['token'] ?? '';
        if (!$token) {
            header("Location: /admin/login");
            exit;
        }

        $usuario = $this->authModel->validarToken($token);

        if (!$usuario) {
            View::render("Auth/Views/reset", ["error" => "Token inválido ou expirado."]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $novaSenha = $_POST['senha'] ?? '';
            $confirmar = $_POST['confirmar'] ?? '';

            if ($novaSenha !== $confirmar) {
                View::render("Auth/Views/reset", [
                    "error" => "As senhas não coincidem.",
                    "token" => $token
                ]);
                return;
            }

            $this->authModel->atualizarSenha($usuario['id'], $novaSenha);

            View::render("Auth/Views/reset", [
                "success" => "Senha redefinida com sucesso! Agora você pode fazer login.",
            ]);
            return;
        }

        View::render("Auth/Views/reset", ["token" => $token]);
    }
}