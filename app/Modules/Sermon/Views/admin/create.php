<?php ob_start(); ?>
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">

    <form method="POST" class="space-y-4">

        <div>
            <label class="block font-semibold mb-1">Título</label>
            <input type="text" name="titulo" required class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Conteúdo</label>
            <textarea name="conteudo" rows="6" class="w-full px-3 py-2 border rounded"></textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Pregador</label>
            <input type="text" name="pregador" class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Data</label>
            <input type="date" name="data" value="<?= date('Y-m-d') ?>" required
                class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border rounded">
                <option value="rascunho">Rascunho</option>
                <option value="publicado">Publicado</option>
            </select>
        </div>

        <!-- Mídias Relacionadas -->
        <div>
            <label class="block font-semibold mb-2">Mídias Relacionadas</label>
            <div class="grid grid-cols-3 gap-4">
                <?php foreach ($midias as $m): ?>
                    <label class="flex flex-col items-center rounded p-2 cursor-pointer hover:shadow">
                        <input type="checkbox" name="midias[]" value="<?= $m['id'] ?>" class="mb-2">
                        <?php if ($m['tipo_arquivo'] === 'imagem'): ?>
                            <img src="/<?= $m['caminho_arquivo'] ?>" class="h-full w-full object-cover rounded">
                        <?php elseif ($m['tipo_arquivo'] === 'video'): ?>
                            <video class="h-full w-full rounded" muted>
                                <source src="/<?= $m['caminho_arquivo'] ?>" type="<?= $m['tipo_mime'] ?>">
                            </video>
                        <?php elseif ($m['tipo_arquivo'] === 'audio'): ?>
                            <div class="h-full w-full bg-gray-100 flex items-center justify-center rounded">🎵</div>
                        <?php elseif ($m['tipo_arquivo'] === 'pdf'): ?>
                            <div class="h-full w-full bg-gray-100 flex items-center justify-center rounded">📄 PDF</div>
                        <?php else: ?>
                            <div class="h-full w-full bg-gray-100 flex items-center justify-center rounded">📄 Documento</div>
                        <?php endif; ?>
                        <span class="text-xs mt-1"><?= htmlspecialchars($m['nome_arquivo']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex justify-between pt-4">
            <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-500">Criar
                Sermão</button>
            <a href="/admin/sermoes" class="text-gray-600 hover:text-gray-800">Cancelar</a>
        </div>

    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout.php";
?>