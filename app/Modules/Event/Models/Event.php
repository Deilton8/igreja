<?php
namespace App\Modules\Event\Models;

use App\Core\Model;
use PDO;

class Event extends Model
{
    protected $table = "eventos";

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
        $stmt = $this->db->prepare("INSERT INTO {$this->table} 
            (titulo, descricao, local, data_inicio, data_fim, status) 
            VALUES (:titulo, :descricao, :local, :data_inicio, :data_fim, :status)");

        $stmt->execute([
            ':titulo' => $data['titulo'],
            ':descricao' => $data['descricao'],
            ':local' => $data['local'],
            ':data_inicio' => $data['data_inicio'],
            ':data_fim' => $data['data_fim'] ?? null,
            ':status' => $data['status'] ?? 'pendente',
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} 
            SET titulo=:titulo, descricao=:descricao, local=:local, data_inicio=:data_inicio, data_fim=:data_fim, status=:status 
            WHERE id=:id");

        return $stmt->execute([
            ':titulo' => $data['titulo'],
            ':descricao' => $data['descricao'],
            ':local' => $data['local'],
            ':data_inicio' => $data['data_inicio'],
            ':data_fim' => $data['data_fim'] ?? null,
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }

    // 🔗 Relacionar mídias com eventos
    public function attachMedia($eventId, $mediaIds = [])
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO midia_eventos (midia_id, evento_id) VALUES (:midia_id, :evento_id)");

        foreach ($mediaIds as $midiaId) {
            $stmt->execute([
                ':midia_id' => $midiaId,
                ':evento_id' => $eventId
            ]);
        }
    }

    public function getMedia($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT m.* 
            FROM midia_eventos me
            JOIN midia m ON me.midia_id = m.id
            WHERE me.evento_id = ?
        ");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detachMedia($eventId, $midiaId)
    {
        $stmt = $this->db->prepare("DELETE FROM midia_eventos WHERE evento_id=? AND midia_id=?");
        return $stmt->execute([$eventId, $midiaId]);
    }

    public function findWithMedia($id)
    {
        $stmt = $this->db->prepare("
        SELECT e.*, m.id AS midia_id, m.nome_arquivo, m.caminho_arquivo, m.tipo_arquivo, m.tipo_mime
        FROM {$this->table} e
        LEFT JOIN midia_eventos me ON me.evento_id = e.id
        LEFT JOIN midia m ON m.id = me.midia_id
        WHERE e.id = ?
    ");
        $stmt->execute([$id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows)
            return null;

        $evento = [
            "id" => $rows[0]['id'],
            "titulo" => $rows[0]['titulo'],
            "descricao" => $rows[0]['descricao'],
            "local" => $rows[0]['local'],
            "data_inicio" => $rows[0]['data_inicio'],
            "data_fim" => $rows[0]['data_fim'],
            "status" => $rows[0]['status'],
            "criado_em" => $rows[0]['criado_em'],
            "atualizado_em" => $rows[0]['atualizado_em'],
            "midias" => []
        ];

        foreach ($rows as $row) {
            if (!empty($row['midia_id'])) {
                $evento['midias'][] = [
                    "id" => $row['midia_id'],
                    "nome_arquivo" => $row['nome_arquivo'],
                    "caminho_arquivo" => $row['caminho_arquivo'],
                    "tipo_arquivo" => $row['tipo_arquivo'],
                    "tipo_mime" => $row['tipo_mime'],
                ];
            }
        }

        return $evento;
    }
}