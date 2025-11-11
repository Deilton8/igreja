<!DOCTYPE html>
<html lang="pt-br" x-data="{ sidebarOpen: true }">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? "Administração" ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-100 flex h-screen">

    <!-- Sidebar -->
    <aside x-show="sidebarOpen" class="w-64 bg-gray-900 text-gray-100 flex flex-col">
        <div class="p-6 text-2xl font-bold border-b border-gray-700">
            Painel Admin
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="/admin/dashboard" class="block px-3 py-2 rounded hover:bg-gray-700">Dashboard</a>
            <a href="/admin/midia" class="block px-3 py-2 rounded hover:bg-gray-700">Mídias</a>
            <a href="/admin/eventos" class="block px-3 py-2 rounded hover:bg-gray-700">Eventos</a>
            <a href="/admin/publicacoes" class="block px-3 py-2 rounded hover:bg-gray-700">Publicações</a>
            <a href="/admin/sermoes" class="block px-3 py-2 rounded hover:bg-gray-700">Sermões</a>
            <a href="/admin/usuarios" class="block px-3 py-2 rounded hover:bg-gray-700">Usuários</a>
        </nav>
    </aside>

    <!-- Conteúdo principal -->
    <main class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="flex justify-between items-center bg-white shadow px-6 py-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-gray-800">
                ☰
            </button>
            <!-- Dropdown do usuário -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['usuario']['nome'] ?? 'Usuario') ?>&background=random"
                        class="w-8 h-8 rounded-full">
                    <span><?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Usuário') ?></span>
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg py-2">
                    <a href="/admin/usuario/<?= $_SESSION['usuario']['id'] ?? '' ?>"
                        class="block px-4 py-2 hover:bg-gray-100">Perfil</a>
                    <a href="/admin/logout" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Sair</a>
                </div>
            </div>
        </header>

        <!-- Conteúdo -->
        <section class="p-6 overflow-y-auto flex-1">
            <?php if (!empty($title)): ?>
                <h1 class="text-2xl font-semibold mb-6"><?= htmlspecialchars($title) ?></h1>
            <?php endif; ?>
            <?= $content ?? '' ?>
        </section>
    </main>

</body>

</html>