<?php
namespace App\Modules\User\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected $table = "usuarios";

    public function all($limit = 50)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($query = '')
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE nome LIKE :q OR email LIKE :q ORDER BY id DESC");
        $stmt->execute([':q' => "%{$query}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($page = 1, $perPage = 10, $search = '', $role = '', $status = '')
    {
        $offset = ($page - 1) * $perPage;
        $where = [];
        $params = [];

        if ($search) {
            $where[] = "(nome LIKE :search OR email LIKE :search)";
            $params[':search'] = "%$search%";
        }
        if ($role) {
            $where[] = "papel = :role";
            $params[':role'] = $role;
        }
        if ($status) {
            $where[] = "status = :status";
            $params[':status'] = $status;
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // Total de registros
        $stmtTotal = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} $whereSQL");
        $stmtTotal->execute($params);
        $total = $stmtTotal->fetchColumn();

        // Registros da página
        $stmt = $this->db->prepare("
        SELECT * FROM {$this->table} 
        $whereSQL
        ORDER BY id DESC 
        LIMIT :limit OFFSET :offset
    ");
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => ceil($total / $perPage),
        ];
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $params = [':email' => $email];
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function create($data)
    {
        if ($this->emailExists($data['email'])) {
            throw new \Exception("O e-mail informado já está em uso.");
        }

        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (nome, email, senha, papel, status, criado_em) 
            VALUES (:nome, :email, :senha, :papel, :status, NOW())
        ");

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
        if ($this->emailExists($data['email'], $id)) {
            throw new \Exception("O e-mail informado já está em uso por outro usuário.");
        }

        $query = "UPDATE {$this->table} 
                  SET nome=:nome, email=:email, papel=:papel, status=:status, atualizado_em=NOW()";

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

    public function toggleStatus($id)
    {
        $user = $this->find($id);
        $newStatus = $user['status'] === 'ativo' ? 'inativo' : 'ativo';
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $newStatus, ':id' => $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}