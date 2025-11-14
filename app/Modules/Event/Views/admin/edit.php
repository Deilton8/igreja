<?php
ob_start();
?>

<div class="max-w-3xl mx-auto mt-10">
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
        <!-- Cabeçalho -->
        <div class="mb-6 flex items-center justify-between border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    ✏️ Editar Evento
                </h2>
                <p class="text-gray-500 text-sm">Atualize as informações do evento abaixo.</p>
            </div>
            <a href="/admin/eventos"
                class="text-gray-500 hover:text-gray-700 transition text-sm font-medium flex items-center gap-1">
                ← Voltar
            </a>
        </div>

        <!-- Formulário -->
        <form method="POST" class="space-y-5">
            <!-- Título -->
            <div>
                <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-1">Título do Evento</label>
                <input type="text" name="titulo" id="titulo" required value="<?= htmlspecialchars($evento['titulo']) ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white">
            </div>

            <!-- Descrição -->
            <div>
                <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-1">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white"><?= htmlspecialchars($evento['descricao']) ?></textarea>
            </div>

            <!-- Local -->
            <div>
                <label for="local" class="block text-sm font-semibold text-gray-700 mb-1">Local</label>
                <input type="text" name="local" id="local" value="<?= htmlspecialchars($evento['local']) ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white">
            </div>

            <!-- Datas -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="data_inicio" class="block text-sm font-semibold text-gray-700 mb-1">Data Início</label>
                    <input type="datetime-local" name="data_inicio" id="data_inicio" required
                        value="<?= date('Y-m-d\TH:i', strtotime($evento['data_inicio'])) ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white">
                </div>
                <div>
                    <label for="data_fim" class="block text-sm font-semibold text-gray-700 mb-1">Data Fim</label>
                    <input type="datetime-local" name="data_fim" id="data_fim"
                        value="<?= $evento['data_fim'] ? date('Y-m-d\TH:i', strtotime($evento['data_fim'])) : '' ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white">
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50 hover:bg-white">
                    <option value="pendente" <?= $evento['status'] === 'pendente' ? 'selected' : '' ?>>🟡 Pendente</option>
                    <option value="em_andamento" <?= $evento['status'] === 'em_andamento' ? 'selected' : '' ?>>🔵 Em
                        andamento</option>
                    <option value="concluido" <?= $evento['status'] === 'concluido' ? 'selected' : '' ?>>🟢 Concluído
                    </option>
                    <option value="cancelado" <?= $evento['status'] === 'cancelado' ? 'selected' : '' ?>>🔴 Cancelado
                    </option>
                </select>
            </div>

            <!-- Mídias relacionadas -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mídias Relacionadas</label>
                <div class="grid grid-cols-3 gap-4">
                    <?php foreach ($midias as $m): ?>
                        <label class="flex flex-col items-center border rounded p-2 cursor-pointer hover:shadow">
                            <input type="checkbox" name="midias[]" value="<?= $m['id'] ?>" class="mb-2"
                                <?= isset($midiasEvento) && in_array($m['id'], $midiasEvento) ? 'checked' : '' ?>>

                            <?php if ($m['tipo_arquivo'] === 'imagem'): ?>
                                <img src="/<?= $m['caminho_arquivo'] ?>" class="h-24 w-full object-cover rounded">
                            <?php elseif ($m['tipo_arquivo'] === 'video'): ?>
                                <video class="h-24 w-full rounded" muted>
                                    <source src="/<?= $m['caminho_arquivo'] ?>" type="<?= $m['tipo_mime'] ?>">
                                </video>
                            <?php elseif ($m['tipo_arquivo'] === 'audio'): ?>
                                <div class="flex items-center justify-center h-24 w-full bg-gray-100 rounded text-xl">
                                    🎵
                                </div>
                            <?php else: ?>
                                <div class="flex items-center justify-center h-24 w-full bg-gray-100 rounded text-xl">
                                    📄
                                </div>
                            <?php endif; ?>

                            <span class="text-xs mt-1 text-center w-full"><?= htmlspecialchars($m['nome_arquivo']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex items-center justify-between pt-6 border-t mt-6">
                <a href="/admin/eventos"
                    class="text-gray-600 hover:text-gray-800 flex items-center gap-1 text-sm font-medium transition">
                    ← Cancelar
                </a>

                <button type="submit"
                    class="bg-gradient-to-r from-green-600 to-green-500 text-white px-6 py-2.5 rounded-lg shadow hover:from-green-500 hover:to-green-400 transition flex items-center gap-2">
                    💾 Atualizar Evento
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout.php";
?>