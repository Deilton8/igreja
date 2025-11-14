<?php ob_start(); ?>

<div x-data="publicationTable()" x-cloak class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

    <!-- Flash -->
    <?php if (!empty($_SESSION['flash'])):
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        ?>
        <div
            class="mb-4 p-3 rounded <?= isset($flash['success']) ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' ?>">
            <?= htmlspecialchars($flash['success'] ?? $flash['error']) ?>
        </div>
    <?php endif; ?>

    <!-- Header e Filtros -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" x-model.debounce.300ms="search" placeholder="Buscar título ou conteúdo..."
                    class="border border-gray-200 pl-10 pr-3 py-2 rounded-lg w-72 focus:ring-2 focus:ring-blue-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-3 top-2.5 h-5 w-5 text-gray-400 pointer-events-none" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                </svg>
            </div>

            <select x-model="filterCategory" class="border border-gray-200 p-2 rounded-lg">
                <option value="">Todas as categorias</option>
                <option value="noticia">Notícia</option>
                <option value="aviso">Aviso</option>
                <option value="blog">Blog</option>
            </select>

            <select x-model="filterStatus" class="border border-gray-200 p-2 rounded-lg">
                <option value="">Todos os status</option>
                <option value="rascunho">Rascunho</option>
                <option value="publicado">Publicado</option>
            </select>

            <div class="flex gap-2 items-center">
                <label class="text-gray-500 text-sm">De:</label>
                <input type="date" x-model="startDate" class="border p-2 rounded-lg">
                <label class="text-gray-500 text-sm">Até:</label>
                <input type="date" x-model="endDate" class="border p-2 rounded-lg">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-gray-500 text-sm">Total: <strong x-text="filtered.length"></strong></span>
            <button @click="clearFilters" class="text-gray-500 text-sm hover:underline">Limpar filtros</button>
            <a href="/admin/publicacao/criar"
                class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-4 py-2 rounded-lg shadow-md">
                + Nova Publicação
            </a>
        </div>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-sm font-semibold tracking-wide">
                <tr>
                    <th class="border-b px-4 py-3 text-left">ID</th>
                    <th class="border-b px-4 py-3 text-left">Título</th>
                    <th class="border-b px-4 py-3 text-left">Categoria</th>
                    <th class="border-b px-4 py-3 text-left">Publicado em</th>
                    <th class="border-b px-4 py-3 text-left">Status</th>
                    <th class="border-b px-4 py-3 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="p in filtered" :key="p.id">
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 transition">
                        <td class="px-4 py-3 text-gray-700 font-medium" x-text="p.id"></td>
                        <td class="px-4 py-3" x-text="p.titulo"></td>
                        <td class="px-4 py-3 text-gray-600" x-text="p.categoria"></td>
                        <td class="px-4 py-3" x-text="p.publicado_em ? p.publicado_em : '—'"></td>
                        <td class="px-4 py-3">
                            <span
                                :class="p.status === 'publicado' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                class="px-2 py-1 rounded-full text-xs font-semibold" x-text="p.status"></span>
                        </td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <a :href="`/admin/publicacao/${p.id}`" class="text-blue-600 hover:text-blue-800">Ver</a>
                            <a :href="`/admin/publicacao/${p.id}/editar`"
                                class="text-green-600 hover:text-green-800">Editar</a>
                            <button type="button" @click="confirmDelete(p.id)"
                                class="text-red-600 hover:text-red-800">Excluir</button>
                        </td>
                    </tr>
                </template>

                <template x-if="filtered.length === 0">
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500 italic">Nenhuma publicação encontrada.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: none;">
        <div @click.away="showModal = false" class="bg-white p-6 rounded-xl shadow-xl max-w-sm w-full text-center">
            <h3 class="text-lg font-semibold mb-2">Excluir publicação?</h3>
            <p class="text-gray-600 mb-4">A ação não pode ser desfeita.</p>
            <div class="flex justify-center gap-3">
                <button @click="showModal = false" class="px-4 py-2 bg-gray-200 rounded">Cancelar</button>
                <a :href="`/admin/publicacao/${toDelete}/deletar`"
                    class="px-4 py-2 bg-red-600 text-white rounded">Excluir</a>
            </div>
        </div>
    </div>
</div>

<script>
    function publicationTable() {
        return {
            search: '',
            filterCategory: '',
            filterStatus: '',
            startDate: '',
            endDate: '',
            showModal: false,
            toDelete: null,
            publications: <?= json_encode($publicacoes, JSON_UNESCAPED_UNICODE) ?>,

            get filtered() {
                return this.publications.filter(p => {
                    const q = this.search.toLowerCase().trim();
                    const matchQ = !q || (p.titulo && p.titulo.toLowerCase().includes(q)) || (p.conteudo && p.conteudo.toLowerCase().includes(q));
                    const matchCategory = !this.filterCategory || p.categoria === this.filterCategory;
                    const matchStatus = !this.filterStatus || p.status === this.filterStatus;

                    let matchStart = true;
                    let matchEnd = true;

                    if (this.startDate) {
                        matchStart = p.publicado_em ? (p.publicado_em >= this.startDate) : false;
                    }
                    if (this.endDate) {
                        matchEnd = p.publicado_em ? (p.publicado_em <= this.endDate + " 23:59:59") : false;
                    }

                    return matchQ && matchCategory && matchStatus && matchStart && matchEnd;
                });
            },

            clearFilters() {
                this.search = '';
                this.filterCategory = '';
                this.filterStatus = '';
                this.startDate = '';
                this.endDate = '';
            },

            confirmDelete(id) {
                this.toDelete = id;
                this.showModal = true;
            }
        }
    }
</script>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout.php";
?>