<?php
ob_start();
?>
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">

    <form method="POST" class="space-y-4">

        <div>
            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" name="titulo" id="titulo" required
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
            <textarea name="descricao" id="descricao" rows="4"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>

        <div>
            <label for="local" class="block text-sm font-medium text-gray-700">Local</label>
            <input type="text" name="local" id="local"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data início</label>
                <input type="datetime-local" name="data_inicio" id="data_inicio" required
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="data_fim" class="block text-sm font-medium text-gray-700">Data fim</label>
                <input type="datetime-local" name="data_fim" id="data_fim"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
                <option value="pendente">Pendente</option>
                <option value="em_andamento">Em andamento</option>
                <option value="concluido">Concluído</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mídias Relacionadas</label>
            <div class="grid grid-cols-3 gap-4">
                <?php foreach ($midias as $m): ?>
                    <label class="flex flex-col items-center border rounded p-2 cursor-pointer hover:shadow">
                        <input type="checkbox" name="midias[]" value="<?= $m['id'] ?>" class="mb-2" <?= isset($midiasEvento) && in_array($m['id'], $midiasEvento) ? 'checked' : '' ?>>

                        <!-- Preview dependendo do tipo -->
                        <?php if ($m['tipo_arquivo'] === 'imagem'): ?>
                            <img src="/<?= $m['caminho_arquivo'] ?>" class="h-full w-full object-cover rounded">
                        <?php elseif ($m['tipo_arquivo'] === 'video'): ?>
                            <video class="h-full w-full object-cover rounded" muted>
                                <source src="/<?= $m['caminho_arquivo'] ?>" type="<?= $m['tipo_mime'] ?>">
                            </video>
                        <?php elseif ($m['tipo_arquivo'] === 'audio'): ?>
                            <div class="flex items-center justify-center h-full w-full bg-gray-100 rounded">
                                🎵
                            </div>
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full w-full bg-gray-100 rounded">
                                📄
                            </div>
                        <?php endif; ?>

                        <span class="text-xs mt-1 text-center w-full"><?= htmlspecialchars($m['nome_arquivo']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-md shadow hover:bg-blue-500 focus:outline-none">
                Criar Evento
            </button>
            <a href="/admin/eventos" class="text-gray-600 hover:text-gray-800">Cancelar</a>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout.php";
?>