<?php ob_start(); ?>

<div class="bg-white shadow rounded-lg p-6" x-data="{
        openDelete: false, 
        openPreview: false, 
        previewType: null, 
        previewSrc: null, 
        selectedId: null, 
        previews: [],
        currentIndex: 0,
        mediaList: [
            <?php foreach ($midias as $m): ?>
                {
                    id: <?= (int) $m['id'] ?>,
                    type: '<?= $m['tipo_arquivo'] ?>',
                    src: '/<?= $m['caminho_arquivo'] ?>',
                    mime: '<?= $m['tipo_mime'] ?>',
                    size: '<?= $m['tamanho'] ?>',
                    name: '<?= htmlspecialchars($m['nome_arquivo']) ?>'
                },
            <?php endforeach; ?>
        ],
        open(index) {
            this.currentIndex = index;
            this.openPreview = true;
        },
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.mediaList.length;
        },
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.mediaList.length) % this.mediaList.length;
        },
        close() {
            this.openPreview = false;
        },
        get currentMedia() {
            return this.mediaList[this.currentIndex];
        }
     }" @keydown.window="
        if (openPreview) {
            if ($event.key === 'ArrowRight') next();
            if ($event.key === 'ArrowLeft') prev();
            if ($event.key === 'Escape') close();
        }
     " x-cloak>

    <!-- Upload -->
    <form action="/admin/midia/criar" method="post" enctype="multipart/form-data"
        class="space-y-4 bg-white shadow rounded-lg p-6">

        <label class="block">
            <span class="text-gray-700">Arquivos</span>
            <input type="file" name="arquivos[]" multiple accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.txt"
                x-ref="fileInput" @change="
                previews = [];
                for (let file of $event.target.files) {
                    if (file.type.includes('image')) {
                        previews.push({ type: 'image', src: URL.createObjectURL(file), name: file.name, file });
                    } else if (file.type.includes('video')) {
                        previews.push({ type: 'video', src: URL.createObjectURL(file), name: file.name, file });
                    } else if (file.type.includes('audio')) {
                        previews.push({ type: 'audio', src: URL.createObjectURL(file), name: file.name, file });
                    } else {
                        previews.push({ type: 'doc', src: null, name: file.name, file });
                    }
                }
           " class="mt-1 block w-full border rounded p-2">
        </label>

        <!-- Preview com opção de remover -->
        <template x-if="previews.length > 0">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                <template x-for="(file, index) in previews" :key="index">
                    <div class="relative rounded overflow-hidden p-1">
                        <!-- Tipos de preview -->
                        <template x-if="file.type === 'image'">
                            <img :src="file.src" class="w-full h-48 object-cover rounded">
                        </template>
                        <template x-if="file.type === 'video'">
                            <video controls class="w-full h-48 object-cover rounded">
                                <source :src="file.src" type="video/mp4">
                            </video>
                        </template>
                        <template x-if="file.type === 'audio'">
                            <audio controls class="w-full mt-8">
                                <source :src="file.src" type="audio/mpeg">
                            </audio>
                        </template>
                        <template x-if="file.type === 'doc'">
                            <div class="flex flex-col items-center justify-center h-48 text-gray-600">
                                📄 <span class="text-xs">Documento</span>
                            </div>
                        </template>

                        <!-- Botão remover -->
                        <button type="button" @click="
                            // remove do array previews
                            previews.splice(index, 1);

                            // recria FileList
                            const dt = new DataTransfer();
                            previews.forEach(p => dt.items.add(p.file));
                            $refs.fileInput.files = dt.files;
                        " class="absolute top-1 right-1 bg-red-600 text-white rounded-full px-2 py-0.5 text-xs">
                            ✕
                        </button>
                        <p class="text-xs text-center mt-1" x-text="file.name"></p>
                    </div>
                </template>
            </div>
        </template>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-4">Salvar</button>
        <a href="/admin/midia" class="ml-3 text-gray-600 hover:underline">Cancelar</a>
    </form>

    <!-- Listagem -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-6">
        <?php foreach ($midias as $index => $m): ?>
            <div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                <?php if ($m['tipo_arquivo'] === 'imagem'): ?>
                    <img src="/<?= $m['caminho_arquivo'] ?>" class="w-full h-48 object-cover cursor-pointer"
                        @click="open(<?= $index ?>)">
                <?php elseif ($m['tipo_arquivo'] === 'video'): ?>
                    <video class="w-full h-48 object-cover cursor-pointer" @click="open(<?= $index ?>)">
                        <source src="/<?= $m['caminho_arquivo'] ?>" type="<?= $m['tipo_mime'] ?>">
                    </video>
                <?php elseif ($m['tipo_arquivo'] === 'audio'): ?>
                    <div class="flex items-center justify-center h-48 bg-gray-100 cursor-pointer" @click="open(<?= $index ?>)">
                        🎵 Áudio
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-center h-48 bg-gray-100 cursor-pointer" @click="open(<?= $index ?>)">
                        📄 Documento
                    </div>
                <?php endif; ?>

                <div class="p-4">
                    <p class="font-bold truncate"><?= htmlspecialchars($m['nome_arquivo']) ?></p>
                    <p class="text-sm"><?= htmlspecialchars($m['tipo_mime']) ?></p>
                    <p class="text-sm text-gray-500"><?= number_format($m['tamanho'] / 1024, 0, ',', '.') ?> KB</p>

                    <div class="flex space-x-3 mt-3"> <button type="button"
                            @click="selectedId = <?= (int) $m['id'] ?>; openDelete = true"
                            class="text-red-600 hover:underline"> Excluir </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal de exclusão -->
    <div x-show="openDelete" x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: none;">

        <div class="bg-white p-6 rounded-lg shadow max-w-xl w-full" @click.away="openDelete = false">
            <h2 class="text-lg font-bold mb-4">Confirmar exclusão</h2>
            <p class="mb-4">Deseja realmente excluir esta mídia?</p>

            <!-- Detalhes da mídia -->
            <template x-if="selectedId !== null">
                <div class="p-2 mb-4 flex items-center space-x-3">
                    <template x-for="media in mediaList" :key="media.id">
                        <template x-if="media.id === selectedId">
                            <div class="flex items-center space-x-3">
                                <!-- Miniatura ou ícone -->
                                <template x-if="media.type === 'imagem'">
                                    <img :src="media.src" class="w-48 h-48 object-cover rounded">
                                </template>
                                <template x-if="media.type === 'video'">
                                    <video class="w-48 h-48 object-cover rounded">
                                        <source :src="media.src" :type="media.mime">
                                    </video>
                                </template>
                                <template x-if="media.type === 'audio'">
                                    <div class="w-48 h-48 flex items-center justify-center bg-gray-100 rounded">🎵
                                    </div>
                                </template>
                                <template x-if="media.type === 'doc'">
                                    <div class="w-48 h-48 flex items-center justify-center bg-gray-100 rounded">📄
                                    </div>
                                </template>

                                <!-- Detalhes textuais -->
                                <div class="text-sm truncate">
                                    <p><strong>Nome:</strong> <span x-text="media.name"></span></p>
                                    <p><strong>Tipo:</strong> <span x-text="media.mime"></span></p>
                                    <p><strong>Tamanho:</strong> <span
                                            x-text="Math.round((media.size||0)/1024) + ' KB'"></span></p>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </template>

            <div class="flex justify-end space-x-3">
                <button type="button" @click="openDelete = false"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-480">Cancelar</button>
                <a :href="`/admin/midia/${selectedId}/deletar`"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">Excluir</a>
            </div>
        </div>
    </div>

    <!-- Modal de visualização -->
    <div x-show="openPreview" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-80 z-50 p-4"
        x-transition.opacity style="display: none;">
        <div class="relative max-w-4xl w-full max-h-screen flex items-center justify-center">

            <!-- Botão fechar -->
            <button @click="close()"
                class="absolute top-2 right-2 bg-red-600 text-white rounded-full px-3 py-1 shadow z-50">
                ✕
            </button>

            <!-- Botão anterior -->
            <button @click="prev()" class="absolute left-2 text-white rounded-full px-3 py-1 shadow z-50">
                ←
            </button>

            <!-- Botão próximo -->
            <button @click="next()" class="absolute right-2 text-white rounded-full px-3 py-1 shadow z-50">
                →
            </button>

            <!-- Conteúdo do preview -->
            <template x-if="currentMedia.type === 'imagem'">
                <img :src="currentMedia.src" class="w-full max-h-screen object-contain rounded-lg">
            </template>

            <template x-if="currentMedia.type === 'video'">
                <video controls muted class="w-full max-h-screen rounded-lg">
                    <source :src="currentMedia.src" :type="currentMedia.mime">
                </video>
            </template>

            <template x-if="currentMedia.type === 'audio'">
                <audio controls autoplay class="w-full">
                    <source :src="currentMedia.src" :type="currentMedia.mime">
                </audio>
            </template>

            <template x-if="currentMedia.type === 'doc'">
                <div class="bg-white w-full h-[80vh] flex items-center justify-center rounded-lg">
                    <iframe :src="currentMedia.src" class="w-full h-full"
                        x-show="currentMedia.src.endsWith('.pdf')"></iframe>
                    <p x-show="!currentMedia.src.endsWith('.pdf')">
                        Visualização não disponível.
                        <a :href="currentMedia.src" class="text-blue-600 underline">Baixar</a>
                    </p>
                </div>
            </template>

        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../Shared/Views/layout.php";
?>