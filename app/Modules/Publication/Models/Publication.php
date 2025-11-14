<?php
namespace App\Modules\Publication\Models;

use App\Core\Model;
use PDO;

class Publication extends Model
{
    protected $table = "publicacoes";

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY publicado_em DESC, id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findBySlug($slug)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $slug = $this->generateSlug($data['titulo']);

        $stmt = $this->db->prepare("INSERT INTO {$this->table} 
            (titulo, slug, conteudo, categoria, status, publicado_em, criado_em) 
            VALUES (:titulo, :slug, :conteudo, :categoria, :status, :publicado_em, NOW())");

        $stmt->execute([
            ':titulo' => $data['titulo'],
            ':slug' => $slug,
            ':conteudo' => $data['conteudo'],
            ':categoria' => $data['categoria'] ?? 'blog',
            ':status' => $data['status'] ?? 'rascunho',
            ':publicado_em' => !empty($data['publicado_em']) ? $data['publicado_em'] : null,
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $slug = $this->generateSlug($data['titulo'], $id);

        $stmt = $this->db->prepare("UPDATE {$this->table} 
            SET titulo=:titulo, slug=:slug, conteudo=:conteudo, categoria=:categoria, status=:status, publicado_em=:publicado_em, atualizado_em=NOW()
            WHERE id=:id");

        return $stmt->execute([
            ':titulo' => $data['titulo'],
            ':slug' => $slug,
            ':conteudo' => $data['conteudo'],
            ':categoria' => $data['categoria'],
            ':status' => $data['status'],
            ':publicado_em' => !empty($data['publicado_em']) ? $data['publicado_em'] : null,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }

    // 🔗 Relacionar mídias com publicações
    public function attachMedia($publicacaoId, $mediaIds = [])
    {
        if (empty($mediaIds))
            return;

        $stmt = $this->db->prepare("INSERT IGNORE INTO midia_publicacoes (midia_id, publicacao_id) VALUES (:midia_id, :publicacao_id)");

        foreach ($mediaIds as $midiaId) {
            $stmt->execute([
                ':midia_id' => $midiaId,
                ':publicacao_id' => $publicacaoId
            ]);
        }
    }

    public function detachAllMedia($publicacaoId)
    {
        $stmt = $this->db->prepare("DELETE FROM midia_publicacoes WHERE publicacao_id = ?");
        return $stmt->execute([$publicacaoId]);
    }

    public function getMedia($publicacaoId)
    {
        $stmt = $this->db->prepare("
            SELECT m.* 
            FROM midia_publicacoes mp
            JOIN midia m ON mp.midia_id = m.id
            WHERE mp.publicacao_id = ?
        ");
        $stmt->execute([$publicacaoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detachMedia($publicacaoId, $midiaId)
    {
        $stmt = $this->db->prepare("DELETE FROM midia_publicacoes WHERE publicacao_id=? AND midia_id=?");
        return $stmt->execute([$publicacaoId, $midiaId]);
    }

    public function findWithMedia($id)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, m.id AS midia_id, m.nome_arquivo, m.caminho_arquivo, m.tipo_arquivo, m.tipo_mime
            FROM {$this->table} p
            LEFT JOIN midia_publicacoes mp ON mp.publicacao_id = p.id
            LEFT JOIN midia m ON m.id = mp.midia_id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows)
            return null;

        $publicacao = [
            "id" => $rows[0]['id'],
            "titulo" => $rows[0]['titulo'],
            "slug" => $rows[0]['slug'],
            "conteudo" => $rows[0]['conteudo'],
            "categoria" => $rows[0]['categoria'],
            "status" => $rows[0]['status'],
            "publicado_em" => $rows[0]['publicado_em'],
            "criado_em" => $rows[0]['criado_em'],
            "atualizado_em" => $rows[0]['atualizado_em'],
            "midias" => []
        ];

        foreach ($rows as $row) {
            if (!empty($row['midia_id'])) {
                $publicacao['midias'][] = [
                    "id" => $row['midia_id'],
                    "nome_arquivo" => $row['nome_arquivo'],
                    "caminho_arquivo" => $row['caminho_arquivo'],
                    "tipo_arquivo" => $row['tipo_arquivo'],
                    "tipo_mime" => $row['tipo_mime'],
                ];
            }
        }

        return $publicacao;
    }

    // 🔑 Função para gerar slug único
    private function generateSlug($titulo, $id = null)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));
        // remove possíveis hifens duplicados e trim
        $slug = preg_replace('/-+/', '-', trim($slug, '-'));

        if (empty($slug)) {
            $slug = 'publicacao';
        }

        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE slug = ?";
        $params = [$slug];

        if ($id) {
            $sql .= " AND id != ?";
            $params[] = $id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $count = $stmt->fetchColumn();

        return $count > 0 ? $slug . '-' . ($count + 1) : $slug;
    }
}