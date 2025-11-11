<?php 
ob_start(); 
?>
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">

    <form method="POST" class="space-y-4">

        <div>
            <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" name="nome" id="nome"
                   value="<?= htmlspecialchars($usuario['nome']) ?>" required
                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email"
                   value="<?= htmlspecialchars($usuario['email']) ?>" required
                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="senha" class="block text-sm font-medium text-gray-700">Senha <span class="text-gray-500 text-xs">(deixe em branco para não alterar)</span></label>
            <input type="password" name="senha" id="senha"
                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="papel" class="block text-sm font-medium text-gray-700">Papel</label>
            <select name="papel" id="papel"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
                <option value="editor" <?= $usuario['papel'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                <option value="admin" <?= $usuario['papel'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
                <option value="ativo" <?= $usuario['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                <option value="inativo" <?= $usuario['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
            </select>
        </div>

        <div class="flex items-center justify-between pt-4">
            <button type="submit"
                class="bg-green-600 text-white px-5 py-2 rounded-md shadow hover:bg-green-500 focus:outline-none">
                Atualizar Usuário
            </button>
            <a href="/admin/usuarios"
               class="text-gray-600 hover:text-gray-800">
                Cancelar
            </a>
        </div>
    </form>
</div>
<?php 
$content = ob_get_clean(); 
include __DIR__ . "/../../Shared/Views/layout.php"; 
?>
