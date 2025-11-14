<?php
ob_start();
?>

<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white shadow-2xl rounded-3xl p-8 border border-gray-100 relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full -mr-20 -mt-20">
        </div>

        <div class="flex items-start gap-6 relative z-10">
            <div
                class="w-28 h-28 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-extrabold shadow-lg">
                <?= strtoupper(substr($publicacao['titulo'], 0, 1)) ?>
            </div>

            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($publicacao['titulo']) ?></h1>
                <p class="text-gray-500 mt-2"><?= $publicacao['categoria'] ?> •
                    <?= $publicacao['publicado_em'] ?? 'Não publicado' ?>
                </p>

                <div class="mt-6 text-gray-700 prose max-w-none">
                    <?= nl2br(htmlspecialchars($publicacao['conteudo'])) ?>
                </div>
            </div>
        </div>

        <?php if (!empty($publicacao['midias'])): ?>
            <div x-data="mediaPreview(<?= htmlspecialchars(json_encode($publicacao['midias'])) ?>)" x-cloak
                class="mt-8 relative z-10">
                <h3 class="text-lg font-semibold mb-3">Mídias</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php foreach ($publicacao['midias'] as $i => $m): ?>
                        <div class="rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition transform shadow-sm hover:shadow-md"
                            @click="open(<?= $i ?>)">
                            <?php if ($m['tipo_arquivo'] === 'imagem'): ?>
                                <img src="/<?= $m['caminho_arquivo'] ?>" class="h-36 w-full object-cover">
                            <?php elseif ($m['tipo_arquivo'] === 'video'): ?>
                                <video class="h-36 w-full object-cover">
                                    <source src="/<?= $m['caminho_arquivo'] ?>" type="<?= $m['tipo_mime'] ?>">
                                </video>
                            <?php elseif ($m['tipo_arquivo'] === 'audio'): ?>
                                <div class="h-36 w-full bg-gray-100 flex items-center justify-center text-xl">🎵</div>
                            <?php else: ?>
                                <div class="h-36 w-full bg-gray-100 flex items-center justify-center text-xl">📄</div>
                            <?php endif; ?>
                            <p class="text-xs mt-1 text-center truncate px-1"><?= htmlspecialchars($m['nome_arquivo']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Preview modal -->
                <div x-show="openPreview" x-transition
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-90 z-50 p-4"
                    style="display: none;">
                    <div class="relative max-w-6xl w-full max-h-screen flex items-center justify-center">
                        <button @click="close()"
                            class="absolute top-2 right-2 bg-white text-black rounded-full px-3 py-1 shadow-lg z-50">✕</button>
                        <button @click="prev()" class="absolute left-2 text-white text-4xl">❮</button>
                        <button @click="next()" class="absolute right-2 text-white text-4xl">❯</button>

                        <template x-if="current().tipo_arquivo === 'imagem'">
                            <img :src="'/' + current().caminho_arquivo"
                                class="w-full max-h-screen object-contain rounded-xl shadow-lg">
                        </template>
                        <template x-if="current().tipo_arquivo === 'video'">
                            <video controls autoplay class="w-full max-h-screen rounded-xl shadow-lg">
                                <source :src="'/' + current().caminho_arquivo" :type="current().tipo_mime">
                            </video>
                        </template>
                        <template x-if="current().tipo_arquivo === 'audio'">
                            <audio controls autoplay class="w-full">
                                <source :src="'/' + current().caminho_arquivo" :type="current().tipo_mime">
                            </audio>
                        </template>
                        <template x-if="!['imagem','video','audio'].includes(current().tipo_arquivo)">
                            <div class="bg-white w-full h-[80vh] flex items-center justify-center rounded-xl shadow-lg">
                                <p>Visualização não disponível. <a :href="'/' + current().caminho_arquivo"
                                        class="text-blue-600 underline">Baixar</a></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-8 flex items-center justify-between">
            <a href="/admin/publicacao/<?= $publicacao['id'] ?>/editar"
                class="bg-yellow-500 text-white px-4 py-2 rounded-lg">Editar</a>
            <a href="/admin/publicacoes" class="text-gray-600 hover:underline">Voltar</a>
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