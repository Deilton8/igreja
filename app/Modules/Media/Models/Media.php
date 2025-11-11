<?php
namespace App\Modules\Media\Models;

use App\Core\Model;
use PDO;

class Media extends Model
{
    protected $table = "midia";

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY criado_em DESC");
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
        $sql = "INSERT INTO {$this->table} 
                (caminho_arquivo, nome_arquivo, tipo_mime, tipo_arquivo, tamanho) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['caminho_arquivo'],
            $data['nome_arquivo'],
            $data['tipo_mime'],
            $data['tipo_arquivo'],
            $data['tamanho']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}