<?php
ob_start();
?>

<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
        <!-- Cabeçalho -->
        <div class="mb-6 flex items-center justify-between border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    👤 Criar Novo Usuário
                </h2>
                <p class="text-gray-500 text-sm">Preencha as informações abaixo para adicionar um novo membro ao
                    sistema.</p>
            </div>
            <a href="/admin/usuarios"
                class="text-gray-500 hover:text-gray-700 transition text-sm font-medium flex items-center gap-1">
                ← Voltar
            </a>
        </div>

        <!-- Formulário -->
        <form method="POST" class="space-y-5">
            <!-- Nome -->
            <div>
                <label for="nome" class="block text-sm font-semibold text-gray-700 mb-1">Nome completo</label>
                <input type="text" name="nome" id="nome" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white">
            </div>

            <!-- Senha -->
            <div>
                <label for="senha" class="block text-sm font-semibold text-gray-700 mb-1">Senha</label>
                <div class="relative">
                    <input type="password" name="senha" id="senha" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white pr-10">
                    <button type="button" onclick="toggleSenha()"
                        class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                        👁️
                    </button>
                </div>
            </div>

            <!-- Papel -->
            <div>
                <label for="papel" class="block text-sm font-semibold text-gray-700 mb-1">Papel</label>
                <select name="papel" id="papel"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white">
                    <option value="editor">📝 Editor</option>
                    <option value="admin">🛠️ Admin</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white">
                    <option value="ativo">🟢 Ativo</option>
                    <option value="inativo">🔴 Inativo</option>
                </select>
            </div>

            <!-- Ações -->
            <div class="flex items-center justify-between pt-6 border-t mt-6">
                <a href="/admin/usuarios"
                    class="text-gray-600 hover:text-gray-800 flex items-center gap-1 text-sm font-medium transition">
                    ← Cancelar
                </a>

                <button type="submit"
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2.5 rounded-lg shadow hover:from-blue-500 hover:to-indigo-500 transition flex items-center gap-2">
                    💾 Criar Usuário
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleSenha() {
        const input = document.getElementById('senha');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../Shared/Views/layout.php";
?>