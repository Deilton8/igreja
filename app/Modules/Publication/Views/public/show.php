<?php
ob_start();
?>

<div class="py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col gap-8">
            <div class="p-6">
                <h1 class="text-3xl font-bold mb-3"><?php echo htmlspecialchars($publicacao['titulo']); ?></h1>

                <div class="flex items-center gap-4 text-gray-600 mb-6">
                    <?php if (!empty($publicacao['publicado_em'])): ?>
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt text-yellow-500 mr-2"></i>
                            <span><?php echo date("d M Y H:i", strtotime($publicacao['publicado_em'])); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($publicacao['categoria'])): ?>
                        <div class="flex items-center">
                            <i class="fas fa-tag text-yellow-500 mr-2"></i>
                            <span><?php echo htmlspecialchars($publicacao['categoria']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="prose max-w-none text-gray-700 mb-8">
                    <?php echo $publicacao['conteudo']; /* assume conteudo já pode conter HTML seguro */ ?>
                </div>

                <!-- Galeria clicável (Alpine.js) -->
                <?php if (!empty($publicacao['midias'])): ?>
                    <div x-data="mediaPreview(<?= htmlspecialchars(json_encode($publicacao['midias']), ENT_QUOTES, 'UTF-8') ?>)"
                        x-init="init()" x-cloak class="mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <?php foreach ($publicacao['midias'] as $i => $m): ?>
                                <div class="cursor-pointer rounded overflow-hidden shadow hover:shadow-lg transition"
                                    @click="open(<?= $i ?>)">
                                    <?php if ($m['tipo_arquivo'] === 'imagem'): ?>
                                        <img src="/<?= ltrim($m['caminho_arquivo'], '/') ?>"
                                            alt="<?= htmlspecialchars($m['nome_arquivo']) ?>" class="w-full h-64 object-cover">
                                    <?php elseif ($m['tipo_arquivo'] === 'video'): ?>
                                        <video class="w-full h-64 object-cover" preload="metadata">
                                            <source src="/<?= ltrim($m['caminho_arquivo'], '/') ?>"
                                                type="<?= htmlspecialchars($m['tipo_mime']) ?>">
                                        </video>
                                    <?php else: ?>
                                        <div class="w-full h-64 flex items-center justify-center bg-gray-100 text-xl">
                                            <?= $m['tipo_arquivo'] === 'audio' ? '🎵 Áudio' : 'Arquivo' ?>
                                        </div>
                                    <?php endif; ?>
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

                                <template x-if="current().tipo_arquivo === 'pdf'">
                                    <div class="bg-white w-full h-[80vh] flex items-center justify-center rounded-lg">
                                        <iframe :src="'/' + current().caminho_arquivo" class="w-full h-full"></iframe>
                                    </div>
                                </template>

                                <template x-if="!['imagem','video','audio','pdf'].includes(current().tipo_arquivo)">
                                    <div class="bg-white w-full h-[80vh] flex items-center justify-center rounded-lg p-6">
                                        <p>
                                            Visualização não disponível.
                                            <a :href="'/' + current().caminho_arquivo"
                                                class="text-blue-600 underline">Baixar</a>
                                        </p>
                                    </div>
                                </template>
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
                                        // Teclado
                                        window.addEventListener('keydown', (e) => {
                                            if (!this.openPreview) return;
                                            if (e.key === "Escape") this.close();
                                            if (e.key === "ArrowRight") this.next();
                                            if (e.key === "ArrowLeft") this.prev();
                                        });

                                        // Fecha ao clicar fora do conteúdo modal (opcional)
                                        document.addEventListener('click', (ev) => {
                                            if (!this.openPreview) return;
                                            const modalContent = document.querySelector('.max-w-5xl');
                                            if (!modalContent) return;
                                            // Se o clique for no backdrop (fora da .max-w-5xl), fecha.
                                            if (!modalContent.contains(ev.target) && !ev.target.closest('[x-show]')) {
                                                // ignorar porque selecionamos o backdrop inteiro; alternativa é não usar
                                            }
                                        }, true);
                                    }
                                }
                            }
                        </script>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout_public.php";
?>