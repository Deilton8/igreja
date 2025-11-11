<?php 
ob_start(); 
?>

<!-- wrapper com x-data que engloba tabela + modal -->
<div x-data="{ openModal: false, selectedUser: null }" x-cloak>

    <div class="bg-white shadow rounded-lg p-6">
        <a href="/admin/usuario/criar" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Novo Usuário</a>

        <table class="w-full mt-6 border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2 text-left">ID</th>
                    <th class="border px-4 py-2 text-left">Nome</th>
                    <th class="border px-4 py-2 text-left">Email</th>
                    <th class="border px-4 py-2 text-left">Papel</th>
                    <th class="border px-4 py-2 text-left">Status</th>
                    <th class="border px-4 py-2 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2"><?= $u['id'] ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($u['nome']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($u['email']) ?></td>
                        <td class="border px-4 py-2"><?= $u['papel'] ?></td>
                        <td class="border px-4 py-2"><?= $u['status'] ?></td>
                        <td class="border px-4 py-2 text-center">
                            <a href="/admin/usuario/<?= $u['id'] ?>" class="text-blue-600 hover:underline">Ver</a> |
                            <a href="/admin/usuario/<?= $u['id'] ?>/editar" class="text-green-600 hover:underline">Editar</a> |
                            <!-- type="button" é importante -->
                            <button type="button" @click="selectedUser = <?= (int) $u['id'] ?>; openModal = true"
                                class="text-red-600 hover:underline">
                                Excluir
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal (usa x-show para manter no DOM e possibilitar transições) -->
    <div x-show="openModal" x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: none;">
        <div @click.away="openModal = false" class="bg-white p-6 rounded-lg shadow max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Confirmar exclusão</h2>
            <p class="mb-4">Tem certeza que deseja excluir este usuário?</p>

            <div class="flex justify-end space-x-3">
                <button type="button" @click="openModal = false"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>

                <!-- ajuste a rota abaixo para a sua rota real (/user/:id/delete ou /usuario/:id/deletar) -->
                <a :href="`/admin/usuario/${selectedUser}/deletar`" @click="openModal = false"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">Excluir</a>
            </div>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../Shared/Views/layout.php";
?>