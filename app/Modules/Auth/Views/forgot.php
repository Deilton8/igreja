<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center h-screen">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">Recuperar Senha</h1>
        <p class="text-gray-600 text-sm mb-6 text-center">Digite seu e-mail para receber um link de redefinição.</p>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= htmlspecialchars($error) ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= htmlspecialchars($success) ?></div>
            <?php if (!empty($link)): ?>
                <div class="text-xs break-words text-gray-600 border border-gray-200 rounded p-2"><?= htmlspecialchars($link) ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="post" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg shadow hover:bg-blue-700 transition font-medium">
                Enviar link de recuperação
            </button>
        </form>

        <p class="text-center text-sm mt-6">
            <a href="/admin/login" class="text-blue-600 hover:underline">Voltar ao login</a>
        </p>
    </div>

</body>

</html>