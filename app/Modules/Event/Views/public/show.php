<?php
ob_start();
?>

<div class="py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col gap-8">
            <div class="p-6">
                <h3 class="text-2xl font-bold mb-4">
                    <?= htmlspecialchars($evento['titulo']) ?>
                </h3>

                <div class="mb-6">
                    <ul class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6 text-gray-600">
                        <li class="flex items-center">
                            <i class="far fa-calendar-alt text-yellow-500 mr-2"></i>
                            <span>
                                <?= date("d.m.y H:i", strtotime($evento['data_inicio'])) ?>
                                <?php if (!empty($evento['data_fim'])): ?>
                                    - <?= date("d.m.y H:i", strtotime($evento['data_fim'])) ?>
                                <?php endif; ?>
                            </span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt text-yellow-500 mr-2"></i>
                            <span><?= htmlspecialchars($evento['local']) ?></span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-4 text-gray-600">
                    <?= nl2br(htmlspecialchars($evento['descricao'])) ?>
                </div>

                <!-- Galeria clicável usando Alpine.js -->
                <?php if (!empty($evento['midias'])): ?>
                    <div x-data="mediaPreview(<?= htmlspecialchars(json_encode($evento['midias'])) ?>)" x-init="init()"
                        x-cloak class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                        <?php foreach ($evento['midias'] as $i => $m): ?>
                            <div class="cursor-pointer" @click="open(<?= $i ?>)">
                                <img src="/<?= $m['caminho_arquivo'] ?>" class="w-full h-64 object-cover rounded-lg shadow">
                            </div>
                        <?php endforeach; ?>

                        <!-- Modal de Preview -->
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
                                    <div class="bg-white w-full h-[80vh] flex items-center justify-center rounded-lg">
                                        <p>
                                            Visualização não disponível.
                                            <a :href="'/' + current().caminho_arquivo"
                                                class="text-blue-600 underline">Baixar</a>
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
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout_public.php";
?>