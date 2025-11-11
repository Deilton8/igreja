<?php
ob_start();
?>

<div x-data="mediaPreview(<?= htmlspecialchars(json_encode($evento['midias'])) ?>)" x-cloak
    class="bg-white shadow rounded-lg p-6 max-w-3xl mx-auto">

    <div class="space-y-3">
        <p><span class="font-semibold text-gray-700">ID:</span> <?= $evento['id'] ?></p>
        <p><span class="font-semibold text-gray-700">Título:</span> <?= htmlspecialchars($evento['titulo']) ?></p>
        <p><span class="font-semibold text-gray-700">Descrição:</span>
            <?= nl2br(htmlspecialchars($evento['descricao'])) ?></p>
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

    <!-- Mídias relacionadas -->
    <?php if (!empty($evento['midias'])): ?>
        <div class="mt-6">
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
                            <div class="flex items-center justify-center h-32 w-full bg-gray-100 rounded text-xl">
                                🎵
                            </div>
                        <?php elseif ($m['tipo_arquivo'] === 'pdf'): ?>
                            <div class="flex items-center justify-center h-32 w-full bg-gray-100 rounded text-xl">
                                📄 PDF
                            </div>
                        <?php else: ?>
                            <div class="flex items-center justify-center h-32 w-full bg-gray-100 rounded text-xl">
                                📄 Documento
                            </div>
                        <?php endif; ?>
                        <p class="text-xs mt-2 truncate"><?= htmlspecialchars($m['nome_arquivo']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-6 flex items-center justify-between">
        <a href="/admin/evento/<?= $evento['id'] ?>/editar"
            class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow hover:bg-yellow-400">
            Editar Evento
        </a>
        <a href="/admin/eventos" class="text-gray-600 hover:text-gray-800">Voltar</a>
    </div>

    <!-- Modal de Preview -->
    <div x-show="openPreview" x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-80 z-50 p-4" style="display: none;">
        <div class="relative max-w-5xl w-full max-h-screen flex items-center justify-center">
            <button @click="close()"
                class="absolute top-2 right-2 bg-white text-black rounded-full px-3 py-1 shadow z-50">✕</button>

            <!-- Navegação -->
            <button @click="prev()" class="absolute left-2 text-white text-3xl">❮</button>
            <button @click="next()" class="absolute right-2 text-white text-3xl">❯</button>

            <!-- Tipos de preview -->
            <template x-if="current().tipo_arquivo === 'imagem'">
                <img :src="'/' + current().caminho_arquivo" class="w-full max-h-screen object-contain rounded-lg">
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

            <template x-if="current().tipo_arquivo === 'pdf'">
                <div class="bg-white w-full h-[80vh] flex items-center justify-center rounded-lg">
                    <iframe :src="'/' + current().caminho_arquivo" class="w-full h-full"></iframe>
                </div>
            </template>

            <template x-if="!['imagem','video','audio','pdf'].includes(current().tipo_arquivo)">
                <div class="bg-white w-full h-[80vh] flex items-center justify-center rounded-lg">
                    <p>
                        Visualização não disponível.
                        <a :href="'/' + current().caminho_arquivo" class="text-blue-600 underline">Baixar</a>
                    </p>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    function mediaPreview(midias) {
        return {
            midias,
            index: 0,
            openPreview: false,

            open(i) {
                this.index = i;
                this.openPreview = true;
            },
            close() {
                this.openPreview = false;
            },
            next() {
                this.index = (this.index + 1) % this.midias.length;
            },
            prev() {
                this.index = (this.index - 1 + this.midias.length) % this.midias.length;
            },
            current() {
                return this.midias[this.index];
            },
            init() {
                // Navegação via teclado
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