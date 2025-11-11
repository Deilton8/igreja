<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? "IMGD - Igreja Ministério da Graça de Deus"; ?></title>

    <!-- Tailwind CSS e FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-100">

    <!-- Header -->
    <?php include __DIR__ . '/partials/header.php'; ?>

    <!-- Conteúdo da página -->
    <main class="">
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.querySelectorAll('.swiper-container').forEach(function (swiperEl) {
            new Swiper(swiperEl, {
                loop: true,              // Loop infinito
                autoplay: {
                    delay: 5000,         // 5 segundos por slide
                    disableOnInteraction: false, // Continua mesmo se o usuário passar o mouse
                },
                pagination: {
                    el: swiperEl.querySelector('.swiper-pagination'),
                    clickable: true,      // Indicadores clicáveis
                },
                // Sem botões de navegação
            });
        });
    </script>

</body>

</html>