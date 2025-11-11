<?php
namespace App\Modules\User\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected $table = "usuarios";

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nome, email, senha, papel, status) VALUES (:nome, :email, :senha, :papel, :status)");
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':senha' => password_hash($data['senha'], PASSWORD_BCRYPT),
            ':papel' => $data['papel'] ?? 'editor',
            ':status' => $data['status'] ?? 'ativo',
        ]);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table} SET nome=:nome, email=:email, papel=:papel, status=:status";
        $params = [
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':papel' => $data['papel'],
            ':status' => $data['status'],
            ':id' => $id
        ];

        if (!empty($data['senha'])) {
            $query .= ", senha=:senha";
            $params[':senha'] = password_hash($data['senha'], PASSWORD_BCRYPT);
        }

        $query .= " WHERE id=:id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id =?");
        return $stmt->execute([$id]);
    }
}