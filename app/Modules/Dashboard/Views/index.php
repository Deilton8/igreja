<?php
ob_start();
?>

<div class="bg-white shadow rounded-lg p-6">
    <p class="text-lg">Bem-vindo, <strong><?= htmlspecialchars($usuario['nome']) ?></strong>!</p>
    <p class="mt-2 text-gray-600">Email: <?= htmlspecialchars($usuario['email']) ?></p>
    <p class="mt-2 text-gray-600">Papel: <?= htmlspecialchars($usuario['papel']) ?></p>
    <p class="mt-2 text-gray-600">Status: <?= htmlspecialchars($usuario['status']) ?></p>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../Shared/Views/layout.php';
?>