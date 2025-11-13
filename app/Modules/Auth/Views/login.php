<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar no Painel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-50 to-indigo-100 flex items-center justify-center h-screen">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Painel Administrativo</h1>
            <p class="text-gray-500 text-sm">Faça login para continuar</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>

            <div>
                <label for="senha" class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" name="senha" id="senha" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="lembrar" class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-600">Lembrar-me</span>
                </label>
                <a href="/admin/esqueci-senha" class="text-blue-600 hover:underline">Esqueceu a senha?</a>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg shadow hover:bg-blue-700 transition font-medium">
                Entrar
            </button>
        </form>
    </div>
</body>

</html>