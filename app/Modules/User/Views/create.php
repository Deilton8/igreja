<?php
ob_start();
?>
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">

    <form method="POST" class="space-y-4">

        <div>
            <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" name="nome" id="nome" required
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" required
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="senha" class="block text-sm font-medium text-gray-700">Senha</label>
            <input type="password" name="senha" id="senha" required
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="papel" class="block text-sm font-medium text-gray-700">Papel</label>
            <select name="papel" id="papel"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
                <option value="editor">Editor</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
            </select>
        </div>

        <div class="flex items-center justify-between pt-4">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-md shadow hover:bg-blue-500 focus:outline-none">
                Criar Usuário
            </button>
            <a href="/admin/usuarios" class="text-gray-600 hover:text-gray-800">
                Cancelar
            </a>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . "/../../Shared/Views/layout.php";
?>