<?php
namespace App\Modules\Auth\Models;

use App\Core\Model;
use PDO;

class Auth extends Model
{
    protected $table = "usuarios";

    public function attempt($email, $senha)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario["senha"])) {
            return $usuario;
        }
        return false;
    }
}