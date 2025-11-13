<?php ob_start(); ?>

<div x-data="userTable()" x-cloak class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

    <!-- 🔍 Filtros -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" x-model.debounce.300ms="search" placeholder="Buscar usuário..."
                    class="border border-gray-200 pl-10 pr-3 py-2 rounded-lg w-64 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-3 top-2.5 h-5 w-5 text-gray-400 pointer-events-none" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                </svg>
            </div>

            <select x-model="filterRole"
                class="border border-gray-200 p-2 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                <option value="">Todos os papéis</option>
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
            </select>

            <select x-model="filterStatus"
                class="border border-gray-200 p-2 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                <option value="">Todos os status</option>
                <option value="ativo">Ativos</option>
                <option value="inativo">Inativos</option>
            </select>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-gray-500 text-sm">Total:
                <strong x-text="filteredUsers.length"></strong>
            </span>
            <button @click="clearFilters"
                class="text-gray-500 text-sm hover:text-gray-700 transition underline-offset-2 hover:underline">
                Limpar filtros
            </button>
            <a href="/admin/usuario/criar"
                class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-4 py-2 rounded-lg hover:from-blue-500 hover:to-blue-400 transition shadow-md">
                + Novo Usuário
            </a>
        </div>
    </div>

    <!-- 📋 Tabela -->
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-sm font-semibold tracking-wide">
                <tr>
                    <th class="border-b px-4 py-3 text-left">ID</th>
                    <th class="border-b px-4 py-3 text-left">Nome</th>
                    <th class="border-b px-4 py-3 text-left">Email</th>
                    <th class="border-b px-4 py-3 text-left">Papel</th>
                    <th class="border-b px-4 py-3 text-left">Status</th>
                    <th class="border-b px-4 py-3 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="user in filteredUsers" :key="user.id">
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 transition">
                        <td class="px-4 py-3 text-gray-700 font-medium" x-text="user.id"></td>
                        <td class="px-4 py-3" x-text="user.nome"></td>
                        <td class="px-4 py-3 text-gray-600" x-text="user.email"></td>
                        <td class="px-4 py-3">
                            <span :class="{
                                'bg-purple-100 text-purple-800': user.papel === 'admin',
                                'bg-blue-100 text-blue-800': user.papel === 'editor'
                            }" class="px-2 py-1 rounded-full text-xs font-semibold capitalize"
                                x-text="user.papel"></span>
                        </td>
                        <td class="px-4 py-3">
                            <span :class="{
                                'bg-green-100 text-green-800': user.status === 'ativo',
                                'bg-red-100 text-red-800': user.status === 'inativo'
                            }" class="px-2 py-1 rounded-full text-xs font-semibold capitalize"
                                x-text="user.status"></span>
                        </td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <a :href="`/admin/usuario/${user.id}`"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver</a>
                            <a :href="`/admin/usuario/${user.id}/editar`"
                                class="text-green-600 hover:text-green-800 text-sm font-medium">Editar</a>
                            <button type="button" @click="confirmDelete(user.id)"
                                class="text-red-600 hover:text-red-800 text-sm font-medium">Excluir</button>
                        </td>
                    </tr>
                </template>

                <template x-if="filteredUsers.length === 0">
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500 italic">Nenhum usuário encontrado.</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <?php if ($pagination['lastPage'] > 1): ?>
        <div class="mt-6 flex justify-center items-center gap-2">
            <?php for ($p = 1; $p <= $pagination['lastPage']; $p++): ?>
                <a href="?page=<?= $p ?>&q=<?= urlencode($search) ?>&role=<?= urlencode($role) ?>&status=<?= urlencode($status) ?>"
                    class="px-3 py-1 rounded-md text-sm font-medium <?= $p == $pagination['page'] ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                    <?= $p ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <!-- 🧾 Modal de confirmação -->
    <div x-show="showModal" x-transition.opacity.duration.250ms
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: none;">
        <div @click.away="showModal = false"
            class="bg-white p-6 rounded-xl shadow-xl max-w-sm w-full text-center transform transition-all scale-100">
            <h2 class="text-lg font-bold text-gray-800 mb-3">Excluir usuário?</h2>
            <p class="text-gray-600 mb-6">Essa ação <strong>não pode ser desfeita</strong>.</p>
            <div class="flex justify-center gap-3">
                <button @click="showModal = false"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancelar
                </button>
                <a :href="`/admin/usuario/${userToDelete}/deletar`"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-500 shadow-sm transition">
                    Excluir
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function userTable() {
        return {
            search: '',
            filterRole: '',
            filterStatus: '',
            showModal: false,
            userToDelete: null,
            users: <?= json_encode($usuarios, JSON_UNESCAPED_UNICODE) ?>,

            get filteredUsers() {
                return this.users.filter(u => {
                    const matchSearch = u.nome.toLowerCase().includes(this.search.toLowerCase())
                        || u.email.toLowerCase().includes(this.search.toLowerCase());
                    const matchRole = !this.filterRole || u.papel === this.filterRole;
                    const matchStatus = !this.filterStatus || u.status === this.filterStatus;
                    return matchSearch && matchRole && matchStatus;
                });
            },

            clearFilters() {
                this.search = '';
                this.filterRole = '';
                this.filterStatus = '';
            },

            confirmDelete(id) {
                this.userToDelete = id;
                this.showModal = true;
            }
        };
    }
</script>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../Shared/Views/layout.php";
?>