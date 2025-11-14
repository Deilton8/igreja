<?php
ob_start();
?>

<div class="max-w-3xl mx-auto mt-10">
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
        <div class="mb-6 flex items-center justify-between border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold">📝 Criar Publicação</h2>
                <p class="text-gray-500 text-sm">Preencha os dados e anexe as mídias desejadas.</p>
            </div>
            <a href="/admin/publicacoes" class="text-gray-500 hover:underline">← Voltar</a>
        </div>

        <?php if (!empty($_SESSION['flash'])):
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']); ?>
            <div
                class="mb-4 p-3 rounded <?= isset($flash['success']) ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' ?>">
                <?= htmlspecialchars($flash['success'] ?? $flash['error']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" name="titulo" required class="w-full p-3 border rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Conteúdo</label>
                <textarea name="conteudo" rows="8" class="w-full p-3 border rounded-lg"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Categoria</label>
                    <select name="categoria" class="w-full p-2 border rounded-lg">
                        <option value="blog">Blog</option>
                        <option value="noticia">Notícia</option>
                        <option value="aviso">Aviso</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="w-full p-2 border rounded-lg">
                        <option value="rascunho">Rascunho</option>
                        <option value="publicado">Publicado</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Publicado em (opcional)</label>
                <input type="datetime-local" name="publicado_em" class="w-full p-2 border rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mídias</label>
                <div class="grid grid-cols-3 gap-3">
                    <?php foreach ($midias as $m): ?>
                        <label class="block border rounded-lg p-2 cursor-pointer hover:shadow">
                            <input type="checkbox" name="midias[]" value="<?= $m['id'] ?>" class="mb-2">
                            <?php if ($m['tipo_arquivo'] === 'imagem'): ?>
                                <img src="/<?= $m['caminho_arquivo'] ?>" class="w-full h-28 object-cover rounded">
                            <?php elseif ($m['tipo_arquivo'] === 'video'): ?>
                                <video class="w-full h-28 object-cover rounded" muted>
                                    <source src="/<?= $m['caminho_arquivo'] ?>">
                                </video>
                            <?php elseif ($m['tipo_arquivo'] === 'audio'): ?>
                                <div class="w-full h-28 flex items-center justify-center bg-gray-100 rounded">🎵</div>
                            <?php else: ?>
                                <div class="w-full h-28 flex items-center justify-center bg-gray-100 rounded">📄</div>
                            <?php endif; ?>
                            <p class="text-xs mt-2 truncate"><?= htmlspecialchars($m['nome_arquivo']) ?></p>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t mt-6">
                <a href="/admin/publicacoes" class="text-gray-600 hover:underline">← Cancelar</a>
                <button type="submit"
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2.5 rounded-lg">Salvar
                    Publicação</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout.php";
?>