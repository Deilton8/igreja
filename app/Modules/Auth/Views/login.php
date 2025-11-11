<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

        <?php if (!empty($error)): ?>
            <p class="bg-red-100 text-red-700 px-4 py-2 mb-4 rounded"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <input type="email" name="email" required
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-500">
            </div>

            <div>
                <label for="senha" class="block text-sm font-medium mb-2">Senha</label>
                <input type="password" name="senha" required
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-500">Entrar</button>
        </form>
    </div>

</body>

</html>