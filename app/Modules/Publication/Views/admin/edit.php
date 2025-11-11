<?php
ob_start();
?>
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">

    <form method="POST" class="space-y-4">

        <div>
            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($publicacao['titulo']) ?>" required
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="conteudo" class="block text-sm font-medium text-gray-700">Conteúdo</label>
            <textarea name="conteudo" id="conteudo" rows="6"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars($publicacao['conteudo']) ?></textarea>
        </div>

        <div>
            <label for="categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
            <select name="categoria" id="categoria"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
                <option value="noticia" <?= $publicacao['categoria'] === 'noticia' ? 'selected' : '' ?>>Notícia</option>
                <option value="aviso" <?= $publicacao['categoria'] === 'aviso' ? 'selected' : '' ?>>Aviso</option>
                <option value="blog" <?= $publicacao['categoria'] === 'blog' ? 'selected' : '' ?>>Blog</option>
            </select>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
                <option value="rascunho" <?= $publicacao['status'] === 'rascunho' ? 'selected' : '' ?>>Rascunho</option>
                <option value="publicado" <?= $publicacao['status'] === 'publicado' ? 'selected' : '' ?>>Publicado</option>
            </select>
        </div>

        <div>
            <label for="publicado_em" class="block text-sm font-medium text-gray-700">Publicado em</label>
            <input type="datetime-local" name="publicado_em" id="publicado_em"
                value="<?= $publicacao['publicado_em'] ? date('Y-m-d\TH:i', strtotime($publicacao['publicado_em'])) : '' ?>"
                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mídias Relacionadas</label>
            <div class="grid grid-cols-3 gap-4">
                <?php foreach ($midias as $m): ?>
                    <label class="flex flex-col items-center border rounded p-2 cursor-pointer hover:shadow">
                        <input type="checkbox" name="midias[]" value="<?= $m['id'] ?>" class="mb-2"
                            <?= in_array($m['id'], $midiasPublicacao) ? 'checked' : '' ?>>

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
                class="bg-green-600 text-white px-5 py-2 rounded-md shadow hover:bg-green-500 focus:outline-none">
                Atualizar Publicação
            </button>
            <a href="/admin/publicacoes" class="text-gray-600 hover:text-gray-800">Cancelar</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout.php";
?>
