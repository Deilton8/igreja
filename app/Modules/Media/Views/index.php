<?php ob_start(); ?>

<div class="bg-white shadow rounded-lg p-6" x-data="{
        openDelete: false, 
        openPreview: false, 
        selectedId: null,
        previews: [],
        currentIndex: 0,
        search: '<?= htmlspecialchars($filters['q'] ?? '') ?>',
        filterType: '<?= htmlspecialchars($filters['tipo'] ?? '') ?>',
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
        get filteredMedia() {
            const q = this.search.toLowerCase();
            return this.mediaList.filter(m => {
                const matchType = this.filterType ? m.type === this.filterType : true;
                const matchSearch = m.name.toLowerCase().includes(q) || m.mime.toLowerCase().includes(q);
                return matchType && matchSearch;
            });
        },
        open(index) { this.currentIndex = index; this.openPreview = true; },
        next() { this.currentIndex = (this.currentIndex + 1) % this.filteredMedia.length; },
        prev() { this.currentIndex = (this.currentIndex - 1 + this.filteredMedia.length) % this.filteredMedia.length; },
        close() { this.openPreview = false; },
        get currentMedia() { return this.filteredMedia[this.currentIndex]; }
     }" @keydown.window="
        if (openPreview) {
            if ($event.key === 'ArrowRight') next();
            if ($event.key === 'ArrowLeft') prev();
            if ($event.key === 'Escape') close();
        }
     " x-cloak>

    <!-- 🔔 Mensagens de feedback -->
    <?php if (!empty($_SESSION['flash'])): ?>
        <?php $flash = $_SESSION['flash'];
        unset($_SESSION['flash']); ?>
        <div
            class="mb-4 p-4 rounded-lg <?= isset($flash['success']) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
            <?= htmlspecialchars($flash['success'] ?? $flash['error'] ?? '') ?>
        </div>
    <?php endif; ?>

    <!-- 🔎 Filtros reativos (visual aprimorado) -->
    <div
        class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 bg-white shadow-sm border border-gray-200 rounded-xl p-4">
        <!-- Área de busca e tipo -->
        <div class="flex flex-col sm:flex-row gap-3 items-center w-full md:w-auto">
            <!-- Campo de busca -->
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    🔍
                </span>
                <input type="text" x-model.debounce.300ms="search" placeholder="Buscar arquivo..." class="pl-9 pr-3 py-2 w-full border border-gray-300 rounded-lg 
                       focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition 
                       placeholder-gray-400 text-sm bg-gray-50 hover:bg-white">
            </div>

            <!-- Filtro por tipo -->
            <div class="relative">
                <select x-model="filterType" class="appearance-none border border-gray-300 rounded-lg pl-3 pr-8 py-2 text-sm
                       focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition 
                       bg-gray-50 hover:bg-white">
                    <option value="">Todos os tipos</option>
                    <option value="imagem">🖼️ Imagens</option>
                    <option value="video">🎬 Vídeos</option>
                    <option value="audio">🎵 Áudios</option>
                    <option value="documento">📄 Documentos</option>
                </select>

                <!-- Ícone dropdown -->
                <span class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                    ▼
                </span>
            </div>
        </div>

        <!-- Botão limpar -->
        <button @click="search = ''; filterType = ''"
            class="text-gray-600 text-sm flex items-center gap-2 hover:text-blue-600 transition">
            <span>🧹 Limpar filtros</span>
        </button>
    </div>

    <!-- 📤 Upload aprimorado -->
    <form action="/admin/midia/criar" method="post" enctype="multipart/form-data" x-data="{ 
        previews: [], 
        dragging: false,
        handleFiles(files) {
            this.previews = [];
            for (let file of files) {
                let type = file.type.includes('image') ? 'image' : 
                           file.type.includes('video') ? 'video' :
                           file.type.includes('audio') ? 'audio' : 'documento';
                this.previews.push({ 
                    type, 
                    src: URL.createObjectURL(file), 
                    name: file.name, 
                    file 
                });
            }
        }
    }" class="space-y-6 bg-white shadow-lg rounded-2xl p-6 border border-gray-200 transition">
        <!-- Título -->
        <div class="text-center">
            <h2 class="text-lg font-semibold text-gray-800">📁 Enviar Arquivos</h2>
            <p class="text-sm text-gray-500 mt-1">Arraste arquivos ou clique para selecionar</p>
        </div>

        <!-- Área de Upload com Drag & Drop -->
        <div x-ref="dropZone" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
            @drop.prevent="dragging = false; handleFiles($event.dataTransfer.files)"
            :class="dragging ? 'border-blue-400 bg-blue-50' : 'border-gray-300 bg-gray-50'"
            class="border-2 border-dashed rounded-xl flex flex-col items-center justify-center p-8 transition cursor-pointer hover:bg-blue-50"
            @click="$refs.fileInput.click()">
            <input type="file" name="arquivos[]" multiple accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.txt"
                x-ref="fileInput" class="hidden" @change="handleFiles($event.target.files)">

            <div class="flex flex-col items-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-blue-400 mb-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 15a4 4 0 004 4h10a4 4 0 004-4V9a4 4 0 00-4-4h-1.5m-2.25-2.25L12 4.5l2.25 2.25M12 4.5v10.5" />
                </svg>
                <p class="font-medium">Solte aqui ou clique para escolher</p>
                <p class="text-xs text-gray-400">Imagens, vídeos, áudios e documentos até 50MB</p>
            </div>
        </div>

        <!-- Previews -->
        <template x-if="previews.length > 0">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                <template x-for="(file, index) in previews" :key="index">
                    <div
                        class="relative group rounded-xl overflow-hidden border border-gray-200 bg-gray-50 shadow-sm hover:shadow-lg transition-all">

                        <!-- Preview visual -->
                        <template x-if="file.type === 'image'">
                            <img :src="file.src" class="w-full h-48 object-cover rounded-lg">
                        </template>

                        <template x-if="file.type === 'video'">
                            <video controls class="w-full h-48 object-cover rounded-lg">
                                <source :src="file.src" type="video/mp4">
                            </video>
                        </template>

                        <template x-if="file.type === 'audio'">
                            <div class="flex flex-col items-center justify-center h-48 text-blue-500">
                                🎵 <span class="text-xs mt-1">Áudio</span>
                            </div>
                        </template>

                        <template x-if="file.type === 'documento'">
                            <div class="flex flex-col items-center justify-center h-48 text-gray-600">
                                📄 <span class="text-xs mt-1">Documento</span>
                            </div>
                        </template>

                        <!-- Nome e botão remover -->
                        <div class="absolute top-2 right-2">
                            <button type="button" @click="
                                previews.splice(index, 1);
                                const dt = new DataTransfer();
                                previews.forEach(p => dt.items.add(p.file));
                                $refs.fileInput.files = dt.files;
                            "
                                class="bg-red-600 text-white rounded-full px-2 py-0.5 text-xs shadow hover:bg-red-700 transition">
                                ✕
                            </button>
                        </div>

                        <p class="text-xs text-center text-gray-700 mt-2 p-1 truncate" x-text="file.name"></p>
                    </div>
                </template>
            </div>
        </template>

        <!-- Botão de envio -->
        <div class="flex justify-end pt-4">
            <button type="submit"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow transition">
                🚀 <span>Enviar Arquivos</span>
            </button>
        </div>
    </form>

    <!-- 🗂️ Listagem com filtro reativo -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8" x-show="filteredMedia.length > 0">
        <template x-for="(m, index) in filteredMedia" :key="m.id">
            <div :class="{
                    'border-2 rounded-lg overflow-hidden shadow hover:shadow-lg transition relative group': true,
                    'border-blue-400': m.type === 'imagem',
                    'border-purple-400': m.type === 'video',
                    'border-green-400': m.type === 'audio',
                    'border-gray-400': m.type === 'documento'
                }">
                <div class="relative cursor-pointer" @click="open(index)">
                    <template x-if="m.type === 'imagem'">
                        <img :src="m.src" class="w-full h-48 object-cover">
                    </template>
                    <template x-if="m.type === 'video'">
                        <video class="w-full h-48 object-cover">
                            <source :src="m.src" :type="m.mime">
                        </video>
                    </template>
                    <template x-if="m.type === 'audio'">
                        <div class="flex items-center justify-center h-48 bg-gray-100 text-4xl">🎵</div>
                    </template>
                    <template x-if="m.type === 'documento'">
                        <div class="flex items-center justify-center h-48 bg-gray-100 text-4xl">📄</div>
                    </template>

                    <!-- Overlay -->
                    <div
                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <span class="text-white text-sm">Clique para visualizar</span>
                    </div>
                </div>

                <div class="p-4 bg-white">
                    <p class="font-bold truncate" x-text="m.name"></p>
                    <p class="text-sm text-gray-500 truncate" x-text="m.mime"></p>
                    <p class="text-xs text-gray-400" x-text="Math.round(m.size / 1024) + ' KB'"></p>

                    <div class="flex justify-between items-center mt-3">
                        <a :href="m.src" target="_blank" class="text-blue-600 text-sm hover:underline">Baixar</a>
                        <button type="button" @click="selectedId = m.id; openDelete = true"
                            class="text-red-600 text-sm hover:underline">Excluir</button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- 🚫 Nenhum resultado -->
    <div x-show="filteredMedia.length === 0" class="text-center text-gray-500 mt-10">
        Nenhum arquivo encontrado 😕
    </div>

    <!-- 📄 Paginação -->
    <?php if ($totalPages > 1): ?>
        <div class="flex justify-center mt-8 space-x-2">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&tipo=<?= urlencode($filters['tipo'] ?? '') ?>&q=<?= urlencode($filters['q'] ?? '') ?>"
                    class="px-3 py-1 rounded border <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-100' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

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
                class="absolute top-2 right-2 bg-white text-black rounded-full px-3 py-1 shadow z-50">
                ✕
            </button>

            <!-- Botão anterior -->
            <button @click="prev()" class="absolute left-2 text-white text-3xl">
                ❮
            </button>

            <!-- Botão próximo -->
            <button @click="next()" class="absolute right-2 text-white text-3xl">
                ❯
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