<?php
namespace App\Modules\Sermon\Models;

use App\Core\Model;
use PDO;

class Sermon extends Model
{
    protected $table = "sermoes";

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY data DESC");
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
        $id = uniqid(); // ID único
        $slug = $this->generateSlug($data['titulo']);

        $stmt = $this->db->prepare("INSERT INTO {$this->table} 
            (id, titulo, slug, conteudo, pregador, data, status) 
            VALUES (:id, :titulo, :slug, :conteudo, :pregador, :data, :status)");

        $stmt->execute([
            ':id' => $id,
            ':titulo' => $data['titulo'],
            ':slug' => $slug,
            ':conteudo' => $data['conteudo'] ?? null,
            ':pregador' => $data['pregador'] ?? null,
            ':data' => $data['data'],
            ':status' => $data['status'] ?? 'rascunho',
        ]);

        return $id;
    }

    public function update($id, $data)
    {
        $slug = $this->generateSlug($data['titulo']);

        $stmt = $this->db->prepare("UPDATE {$this->table} SET 
            titulo=:titulo, slug=:slug, conteudo=:conteudo, pregador=:pregador, data=:data, status=:status
            WHERE id=:id");

        return $stmt->execute([
            ':titulo' => $data['titulo'],
            ':slug' => $slug,
            ':conteudo' => $data['conteudo'] ?? null,
            ':pregador' => $data['pregador'] ?? null,
            ':data' => $data['data'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }

    // 🔗 Relacionar mídias
    public function attachMedia($sermaoId, $mediaIds = [])
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO midia_sermoes (midia_id, sermao_id) VALUES (:midia_id, :sermao_id)");
        foreach ($mediaIds as $midiaId) {
            $stmt->execute([
                ':midia_id' => $midiaId,
                ':sermao_id' => $sermaoId
            ]);
        }
    }

    public function getMedia($sermaoId)
    {
        $stmt = $this->db->prepare("
            SELECT m.* 
            FROM midia_sermoes ms
            JOIN midia m ON ms.midia_id = m.id
            WHERE ms.sermao_id = ?
        ");
        $stmt->execute([$sermaoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detachMedia($sermaoId, $midiaId)
    {
        $stmt = $this->db->prepare("DELETE FROM midia_sermoes WHERE sermao_id=? AND midia_id=?");
        return $stmt->execute([$sermaoId, $midiaId]);
    }

    public function findWithMedia($id)
    {
        $stmt = $this->db->prepare("
            SELECT s.*, m.id AS midia_id, m.nome_arquivo, m.caminho_arquivo, m.tipo_arquivo, m.tipo_mime
            FROM {$this->table} s
            LEFT JOIN midia_sermoes ms ON ms.sermao_id = s.id
            LEFT JOIN midia m ON m.id = ms.midia_id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows)
            return null;

        $sermao = [
            "id" => $rows[0]['id'],
            "titulo" => $rows[0]['titulo'],
            "slug" => $rows[0]['slug'],
            "conteudo" => $rows[0]['conteudo'],
            "pregador" => $rows[0]['pregador'],
            "data" => $rows[0]['data'],
            "status" => $rows[0]['status'],
            "criado_em" => $rows[0]['criado_em'],
            "atualizado_em" => $rows[0]['atualizado_em'],
            "midias" => []
        ];

        foreach ($rows as $row) {
            if (!empty($row['midia_id'])) {
                $sermao['midias'][] = [
                    "id" => $row['midia_id'],
                    "nome_arquivo" => $row['nome_arquivo'],
                    "caminho_arquivo" => $row['caminho_arquivo'],
                    "tipo_arquivo" => $row['tipo_arquivo'],
                    "tipo_mime" => $row['tipo_mime'],
                ];
            }
        }

        return $sermao;
    }

    // 🔗 Função para gerar slug
    private function generateSlug($titulo)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE slug = ?");
        $count = 0;
        $baseSlug = $slug;
        while (true) {
            $stmt->execute([$slug]);
            if ($stmt->fetchColumn() == 0)
                break;
            $count++;
            $slug = $baseSlug . '-' . $count;
        }
        return $slug;
    }
}