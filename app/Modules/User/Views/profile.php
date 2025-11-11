<?php
ob_start();
?>
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">

    <div class="space-y-3">
        <p><span class="font-semibold text-gray-700">ID:</span> <?= $usuario['id'] ?></p>
        <p><span class="font-semibold text-gray-700">Nome:</span> <?= htmlspecialchars($usuario['nome']) ?></p>
        <p><span class="font-semibold text-gray-700">Email:</span> <?= htmlspecialchars($usuario['email']) ?></p>
        <p><span class="font-semibold text-gray-700">Papel:</span>
            <span
                class="px-2 py-1 rounded text-sm <?= $usuario['papel'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                <?= $usuario['papel'] ?>
            </span>
        </p>
        <p><span class="font-semibold text-gray-700">Status:</span>
            <span
                class="px-2 py-1 rounded text-sm <?= $usuario['status'] === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                <?= $usuario['status'] ?>
            </span>
        </p>
    </div>

    <div class="mt-6 flex items-center justify-between">
        <a href="/admin/usuario/<?= $usuario['id'] ?>/editar"
            class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow hover:bg-yellow-400">
            Editar Usuário
        </a>
        <a href="/admin/usuarios" class="text-gray-600 hover:text-gray-800">
            Voltar
        </a>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . "/../../Shared/Views/layout.php";
?>