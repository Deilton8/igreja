<?php
namespace App\Modules\Media\Models;

use App\Core\Model;
use PDO;

class Media extends Model
{
    protected $table = "midia";

    public function all($filters = [], $limit = 50, $offset = 0)
    {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['tipo'])) {
            $query .= " AND tipo_arquivo = :tipo";
            $params[':tipo'] = $filters['tipo'];
        }

        if (!empty($filters['q'])) {
            $query .= " AND nome_arquivo LIKE :q";
            $params[':q'] = "%{$filters['q']}%";
        }

        $query .= " ORDER BY criado_em DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $val)
            $stmt->bindValue($key, $val);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count($filters = [])
    {
        $query = "SELECT COUNT(*) AS total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['tipo'])) {
            $query .= " AND tipo_arquivo = :tipo";
            $params[':tipo'] = $filters['tipo'];
        }

        if (!empty($filters['q'])) {
            $query .= " AND nome_arquivo LIKE :q";
            $params[':q'] = "%{$filters['q']}%";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (caminho_arquivo, nome_arquivo, tipo_mime, tipo_arquivo, tamanho, criado_em) 
                VALUES (:caminho_arquivo, :nome_arquivo, :tipo_mime, :tipo_arquivo, :tamanho, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':caminho_arquivo' => $data['caminho_arquivo'],
            ':nome_arquivo' => $data['nome_arquivo'],
            ':tipo_mime' => $data['tipo_mime'],
            ':tipo_arquivo' => $data['tipo_arquivo'],
            ':tamanho' => $data['tamanho']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}