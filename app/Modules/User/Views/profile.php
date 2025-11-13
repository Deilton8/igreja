<?php
ob_start();
?>

<div class="max-w-3xl mx-auto mt-10">
    <!-- Card principal -->
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100 relative overflow-hidden">

        <!-- Círculo decorativo -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-full -mr-16 -mt-16"></div>

        <!-- Cabeçalho do perfil -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start sm:space-x-6 relative z-10">
            <div
                class="w-28 h-28 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-md">
                <?= strtoupper(substr($usuario['nome'], 0, 1)) ?>
            </div>

            <div class="mt-4 sm:mt-0 text-center sm:text-left">
                <h2 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($usuario['nome']) ?></h2>
                <p class="text-gray-500"><?= htmlspecialchars($usuario['email']) ?></p>
                <p class="mt-1">
                    <span class="px-3 py-1 rounded-full text-sm font-medium <?= $usuario['papel'] === 'admin'
                        ? 'bg-purple-100 text-purple-800'
                        : 'bg-blue-100 text-blue-800' ?>">
                        <?= ucfirst($usuario['papel']) ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Informações detalhadas -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-700">
            <div class="flex items-center gap-3">
                <div class="bg-gray-100 p-2 rounded-lg text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 15c2.486 0 4.847.635 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">ID do Usuário</p>
                    <p class="font-semibold"><?= $usuario['id'] ?></p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="bg-gray-100 p-2 rounded-lg text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12h3m-6 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="px-3 py-1 rounded-full text-sm font-medium <?= $usuario['status'] === 'ativo'
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800' ?>">
                        <?= ucfirst($usuario['status']) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="mt-10 flex items-center justify-between flex-wrap gap-3">
            <a href="/admin/usuario/<?= $usuario['id'] ?>/editar"
                class="bg-gradient-to-r from-yellow-500 to-amber-400 text-white px-5 py-2.5 rounded-lg shadow hover:from-yellow-400 hover:to-amber-300 transition">
                ✏️ Editar Usuário
            </a>

            <a href="/admin/usuarios"
                class="text-gray-600 hover:text-gray-800 hover:underline transition flex items-center gap-1">
                ← Voltar à lista
            </a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../Shared/Views/layout.php";
?>