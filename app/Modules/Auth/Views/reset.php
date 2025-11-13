<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center h-screen">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Redefinir Senha</h1>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= htmlspecialchars($error) ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= htmlspecialchars($success) ?></div>
            <p class="text-center text-sm mt-4">
                <a href="/admin/login" class="text-blue-600 hover:underline">Fazer login</a>
            </p>
        <?php else: ?>
            <form method="post" class="space-y-5">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
                <div>
                    <label for="senha" class="block text-sm font-medium text-gray-700">Nova Senha</label>
                    <input type="password" name="senha" id="senha" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>

                <div>
                    <label for="confirmar" class="block text-sm font-medium text-gray-700">Confirmar Senha</label>
                    <input type="password" name="confirmar" id="confirmar" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>

                <button type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-lg shadow hover:bg-green-700 transition font-medium">
                    Redefinir senha
                </button>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>