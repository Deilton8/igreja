<?php
ob_start();
?>

<div class="max-w-4xl mx-auto mt-10">
    <!-- Card principal -->
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100 relative overflow-hidden">

        <!-- Círculo decorativo -->
        <div class="absolute top-5 right-5 w-32 h-32 bg-blue-100 rounded-full -mr-16 -mt-16"></div>

        <!-- Cabeçalho do evento -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start sm:space-x-6 relative z-10">
            <div class="mt-4 sm:mt-0 text-center sm:text-left">
                <h2 class="text-2xl font-bold text-gray-800">
                    <?= htmlspecialchars($evento['titulo']) ?>
                </h2>
                <p class="text-gray-500"><?= htmlspecialchars($evento['descricao']) ?></p>
            </div>
        </div>

        <!-- Informações detalhadas -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-700 relative z-10">
            <div class="space-y-2">
                <p><span class="font-semibold text-gray-700">ID:</span> <?= $evento['id'] ?></p>
                <p><span class="font-semibold text-gray-700">Local:</span> <?= htmlspecialchars($evento['local']) ?></p>
                <p><span class="font-semibold text-gray-700">Início:</span> <?= $evento['data_inicio'] ?></p>
                <p><span class="font-semibold text-gray-700">Fim:</span> <?= $evento['data_fim'] ?></p>
                <p><span class="font-semibold text-gray-700">Status:</span>
                    <span
                        class="px-2 py-1 rounded text-sm
                        <?= $evento['status'] === 'concluido' ? 'bg-green-100 text-green-800' :
                            ($evento['status'] === 'em_andamento' ? 'bg-blue-100 text-blue-800' :
                                ($evento['status'] === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) ?>">
                        <?= $evento['status'] ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Mídias relacionadas -->
        <?php if (!empty($evento['midias'])): ?>
            <div x-data="mediaPreview(<?= htmlspecialchars(json_encode($evento['midias'])) ?>)" x-cloak
                class="mt-6 relative z-10">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php foreach ($evento['midias'] as $i => $m): ?>
                        <div class="rounded p-2 flex flex-col items-center cursor-pointer" @click="open(<?= $i ?>)">
                            <?php if ($m['tipo_arquivo'] === 'imagem'): ?>
                                <img src="/<?= $m['caminho_arquivo'] ?>" class="h-32 w-full object-cover rounded">
                            <?php elseif ($m['tipo_arquivo'] === 'video'): ?>
                                <video class="h-32 w-full rounded">
                                    <source src="/<?= $m['caminho_arquivo'] ?>" type="<?= $m['tipo_mime'] ?>">
                                </video>
                            <?php elseif ($m['tipo_arquivo'] === 'audio'): ?>
                                <div class="flex items-center justify-center h-32 w-full bg-gray-100 rounded text-xl">🎵</div>
                            <?php else: ?>
                                <div class="flex items-center justify-center h-32 w-full bg-gray-100 rounded text-xl">📄</div>
                            <?php endif; ?>
                            <p class="text-xs mt-2 truncate"><?= htmlspecialchars($m['nome_arquivo']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Modal Preview -->
                <div x-show="openPreview" x-transition
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-80 z-50 p-4"
                    style="display: none;">
                    <div class="relative max-w-5xl w-full max-h-screen flex items-center justify-center">
                        <button @click="close()"
                            class="absolute top-2 right-2 bg-white text-black rounded-full px-3 py-1 shadow z-50">✕</button>
                        <button @click="prev()" class="absolute left-2 text-white text-3xl">❮</button>
                        <button @click="next()" class="absolute right-2 text-white text-3xl">❯</button>

                        <template x-if="current().tipo_arquivo === 'imagem'">
                            <img :src="'/' + current().caminho_arquivo"
                                class="w-full max-h-screen object-contain rounded-lg">
                        </template>
                        <template x-if="current().tipo_arquivo === 'video'">
                            <video controls autoplay class="w-full max-h-screen rounded-lg">
                                <source :src="'/' + current().caminho_arquivo" :type="current().tipo_mime">
                            </video>
                        </template>
                        <template x-if="current().tipo_arquivo === 'audio'">
                            <audio controls autoplay class="w-full">
                                <source :src="'/' + current().caminho_arquivo" :type="current().tipo_mime">
                            </audio>
                        </template>
                        <template x-if="!['imagem','video','audio'].includes(current().tipo_arquivo)">
                            <div class="bg-white w-full h-[80vh] flex items-center justify-center rounded-lg">
                                <p>Visualização não disponível. <a :href="'/' + current().caminho_arquivo"
                                        class="text-blue-600 underline">Baixar</a></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Ações -->
        <div class="mt-10 flex items-center justify-between flex-wrap gap-3">
            <a href="/admin/evento/<?= $evento['id'] ?>/editar"
                class="bg-gradient-to-r from-yellow-500 to-amber-400 text-white px-5 py-2.5 rounded-lg shadow hover:from-yellow-400 hover:to-amber-300 transition">
                ✏️ Editar Evento
            </a>
            <a href="/admin/eventos"
                class="text-gray-600 hover:text-gray-800 hover:underline transition flex items-center gap-1">← Voltar à
                lista</a>
        </div>
    </div>
</div>

<script>
    function mediaPreview(midias) {
        return {
            midias,
            index: 0,
            openPreview: false,
            open(i) { this.index = i; this.openPreview = true; },
            close() { this.openPreview = false; },
            next() { this.index = (this.index + 1) % this.midias.length; },
            prev() { this.index = (this.index - 1 + this.midias.length) % this.midias.length; },
            current() { return this.midias[this.index]; },
            init() {
                window.addEventListener('keydown', (e) => {
                    if (!this.openPreview) return;
                    if (e.key === "Escape") this.close();
                    if (e.key === "ArrowRight") this.next();
                    if (e.key === "ArrowLeft") this.prev();
                });
            }
        }
    }
</script>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout.php";
?>