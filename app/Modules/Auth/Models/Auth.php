<?php
namespace App\Modules\Auth\Models;

use App\Core\Model;
use PDO;
use DateTime;

class Auth extends Model
{
    protected $table = "usuarios";

    // Tentativas falhas por IP ou e-mail (poderia estar em uma tabela separada)
    private $maxTentativas = 5;
    private $bloqueioMinutos = 10;

    public function attempt($email, $senha)
    {
        // Verifica bloqueio
        if ($this->estaBloqueado($email)) {
            return ['error' => 'Muitas tentativas falhas. Tente novamente em alguns minutos.'];
        }

        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario["senha"])) {
            if ($usuario['status'] !== 'ativo') {
                return ['error' => 'Conta inativa. Contate o administrador.'];
            }

            $this->resetarTentativas($email);
            return $usuario;
        }

        $this->registrarTentativaFalha($email);
        return ['error' => 'Email ou senha inválidos.'];
    }

    private function registrarTentativaFalha($email)
    {
        $key = "login_attempts_" . md5($email);
        $tentativas = $_SESSION[$key]['count'] ?? 0;
        $_SESSION[$key] = [
            'count' => $tentativas + 1,
            'time' => time()
        ];
    }

    private function resetarTentativas($email)
    {
        $key = "login_attempts_" . md5($email);
        unset($_SESSION[$key]);
    }

    private function estaBloqueado($email)
    {
        $key = "login_attempts_" . md5($email);
        if (!isset($_SESSION[$key]))
            return false;

        $tentativas = $_SESSION[$key]['count'];
        $tempo = $_SESSION[$key]['time'];

        if ($tentativas >= $this->maxTentativas && (time() - $tempo) < ($this->bloqueioMinutos * 60)) {
            return true;
        }

        if ((time() - $tempo) >= ($this->bloqueioMinutos * 60)) {
            unset($_SESSION[$key]);
        }

        return false;
    }

    public function gerarTokenLogin($usuarioId)
    {
        $token = bin2hex(random_bytes(32));
        setcookie('remember_token', $token, time() + 864000, '/', '', false, true); // 10 dias
        $stmt = $this->db->prepare("UPDATE {$this->table} SET remember_token = ? WHERE id = ?");
        $stmt->execute([$token, $usuarioId]);
    }

    public function autenticarPorToken($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE remember_token = ? LIMIT 1");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function gerarTokenRecuperacao($email)
    {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario)
            return false;

        $token = bin2hex(random_bytes(32));
        $expira = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

        $stmt = $this->db->prepare("UPDATE {$this->table} SET reset_token = ?, reset_expires = ? WHERE id = ?");
        $stmt->execute([$token, $expira, $usuario['id']]);

        return $token;
    }

    public function validarToken($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE reset_token = ? LIMIT 1");
        $stmt->execute([$token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario)
            return false;

        if (new DateTime() > new DateTime($usuario['reset_expires'])) {
            return false;
        }

        return $usuario;
    }

    public function atualizarSenha($usuarioId, $novaSenha)
    {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE {$this->table} 
                                    SET senha = ?, reset_token = NULL, reset_expires = NULL 
                                    WHERE id = ?");
        return $stmt->execute([$hash, $usuarioId]);
    }
}