<?php ob_start(); ?>

<!-- Informações do usuário -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-6 mb-6 shadow-md">
    <p class="text-xl">Bem-vindo, <strong><?= htmlspecialchars($usuario['nome']) ?></strong>!</p>
    <p class="text-sm opacity-90 mt-1"><?= htmlspecialchars($usuario['email']) ?></p>
    <p class="mt-2 text-sm">Papel: <?= htmlspecialchars($usuario['papel']) ?> | Status:
        <?= htmlspecialchars($usuario['status']) ?></p>
</div>

<!-- Cards de estatísticas aprimorados -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">

    <?php
    $icons = [
        'usuarios' => ['👤', 'from-blue-500 to-blue-700'],
        'publicacoes' => ['📰', 'from-green-500 to-emerald-600'],
        'eventos' => ['📅', 'from-yellow-500 to-amber-600'],
        'sermoes' => ['🎤', 'from-indigo-500 to-indigo-700'],
        'midia' => ['🎞️', 'from-pink-500 to-rose-600'],
        'mensagens_contato' => ['✉️', 'from-purple-500 to-violet-600'],
    ];

    foreach ($estatisticas as $nome => $total):
        $label = ucfirst(str_replace('_', ' ', $nome));
        [$emoji, $gradient] = $icons[$nome] ?? ['📦', 'from-gray-500 to-gray-700'];
        ?>
        <div
            class="relative overflow-hidden bg-gradient-to-r <?= $gradient ?> rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
            <div
                class="absolute inset-0 opacity-10 bg-[url('https://www.toptal.com/designers/subtlepatterns/patterns/dot-grid.png')]">
            </div>

            <div class="p-5 flex items-center justify-between relative z-10 text-white">
                <div>
                    <p class="text-sm font-medium opacity-90"><?= $label ?></p>
                    <p class="text-4xl font-bold mt-1"><?= number_format($total, 0, ',', '.') ?></p>
                </div>
                <div class="text-5xl opacity-80"><?= $emoji ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Gráficos -->
<div class="grid md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Eventos por Status</h2>
        <canvas id="chartEventos"></canvas>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Publicações por Categoria</h2>
        <canvas id="chartPublicacoes"></canvas>
    </div>
</div>

<!-- Listas recentes -->
<div class="grid md:grid-cols-3 gap-6">
    <!-- Próximos eventos -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-3">Próximos Eventos</h2>
        <?php if (!empty($eventos)): ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($eventos as $evento): ?>
                    <li class="py-2">
                        <strong><?= htmlspecialchars($evento['titulo']) ?></strong><br>
                        <span
                            class="text-gray-500 text-sm"><?= date("d/m/Y H:i", strtotime($evento['data_inicio'])) ?></span><br>
                        <span class="text-xs text-blue-600 uppercase"><?= htmlspecialchars($evento['status']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">Nenhum evento futuro.</p>
        <?php endif; ?>
    </div>

    <!-- Últimas publicações -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-3">Últimas Publicações</h2>
        <?php if (!empty($publicacoes)): ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($publicacoes as $pub): ?>
                    <li class="py-2">
                        <strong><?= htmlspecialchars($pub['titulo']) ?></strong><br>
                        <span class="text-gray-500 text-sm"><?= htmlspecialchars($pub['categoria']) ?> —
                            <?= date("d/m/Y", strtotime($pub['publicado_em'])) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">Nenhuma publicação recente.</p>
        <?php endif; ?>
    </div>

    <!-- Últimos sermões -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-3">Últimos Sermões</h2>
        <?php if (!empty($sermoes)): ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($sermoes as $sermao): ?>
                    <li class="py-2">
                        <strong><?= htmlspecialchars($sermao['titulo']) ?></strong><br>
                        <span class="text-gray-500 text-sm"><?= htmlspecialchars($sermao['pregador'] ?? 'Desconhecido') ?> —
                            <?= date("d/m/Y", strtotime($sermao['data'])) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">Nenhum sermão publicado.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const eventosData = <?= json_encode($eventosPorStatus) ?>;
    const publicacoesData = <?= json_encode($publicacoesPorCategoria) ?>;

    // Eventos por status
    new Chart(document.getElementById('chartEventos'), {
        type: 'doughnut',
        data: {
            labels: eventosData.map(e => e.status),
            datasets: [{
                data: eventosData.map(e => e.total),
                backgroundColor: ['#fbbf24', '#3b82f6', '#10b981', '#ef4444'],
            }]
        }
    });

    // Publicações por categoria
    new Chart(document.getElementById('chartPublicacoes'), {
        type: 'bar',
        data: {
            labels: publicacoesData.map(p => p.categoria),
            datasets: [{
                label: 'Total',
                data: publicacoesData.map(p => p.total),
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../Shared/Views/layout.php';
?>