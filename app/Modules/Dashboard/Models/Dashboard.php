<?php
namespace App\Modules\Dashboard\Models;

use App\Core\Model;
use PDO;

class Dashboard extends Model
{
    protected $tables = [
        'usuarios',
        'publicacoes',
        'eventos',
        'sermoes',
        'midia',
        'mensagens_contato'
    ];

    public function getEstatisticas()
    {
        $estatisticas = [];
        foreach ($this->tables as $tabela) {
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM {$tabela}");
            $estatisticas[$tabela] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        }
        return $estatisticas;
    }

    public function getProximosEventos($limite = 5)
    {
        $stmt = $this->db->prepare("
            SELECT id, titulo, data_inicio, local, status
            FROM eventos
            WHERE data_inicio >= NOW()
            ORDER BY data_inicio ASC
            LIMIT :limite
        ");
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUltimasPublicacoes($limite = 5)
    {
        $stmt = $this->db->prepare("
            SELECT id, titulo, categoria, publicado_em
            FROM publicacoes
            WHERE status = 'publicado'
            ORDER BY publicado_em DESC
            LIMIT :limite
        ");
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUltimosSermoes($limite = 5)
    {
        $stmt = $this->db->prepare("
            SELECT id, titulo, pregador, data
            FROM sermoes
            WHERE status = 'publicado'
            ORDER BY data DESC
            LIMIT :limite
        ");
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 📊 Dados para gráficos

    public function getEventosPorStatus()
    {
        $sql = "SELECT status, COUNT(*) AS total FROM eventos GROUP BY status";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublicacoesPorCategoria()
    {
        $sql = "SELECT categoria, COUNT(*) AS total FROM publicacoes WHERE status = 'publicado' GROUP BY categoria";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}