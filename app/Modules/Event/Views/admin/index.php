<?php
ob_start();
?>

<div x-data="{ openModal: false, selectedEvent: null }" x-cloak>

    <div class="bg-white shadow rounded-lg p-6">
        <a href="/admin/evento/criar" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Novo Evento</a>

        <table class="w-full mt-6 border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2 text-left">ID</th>
                    <th class="border px-4 py-2 text-left">Título</th>
                    <th class="border px-4 py-2 text-left">Local</th>
                    <th class="border px-4 py-2 text-left">Início</th>
                    <th class="border px-4 py-2 text-left">Fim</th>
                    <th class="border px-4 py-2 text-left">Status</th>
                    <th class="border px-4 py-2 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eventos as $e): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2"><?= $e['id'] ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($e['titulo']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($e['local']) ?></td>
                        <td class="border px-4 py-2"><?= $e['data_inicio'] ?></td>
                        <td class="border px-4 py-2"><?= $e['data_fim'] ?></td>
                        <td class="border px-4 py-2"><?= $e['status'] ?></td>
                        <td class="border px-4 py-2 text-center">
                            <a href="/admin/evento/<?= $e['id'] ?>" class="text-blue-600 hover:underline">Ver</a> |
                            <a href="/admin/evento/<?= $e['id'] ?>/editar" class="text-green-600 hover:underline">Editar</a>
                            |
                            <button type="button" @click="selectedEvent = <?= (int) $e['id'] ?>; openModal = true"
                                class="text-red-600 hover:underline">
                                Excluir
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de exclusão -->
    <div x-show="openModal" x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: none;">
        <div @click.away="openModal = false" class="bg-white p-6 rounded-lg shadow max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Confirmar exclusão</h2>
            <p class="mb-4">Tem certeza que deseja excluir este evento?</p>

            <div class="flex justify-end space-x-3">
                <button type="button" @click="openModal = false"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <a :href="`/admin/evento/${selectedEvent}/deletar`"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">Excluir</a>
            </div>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout.php";
?>