<?php
ob_start();
?>

<div class="py-16 bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Sermões</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Ouça e leia os últimos sermões da nossa comunidade.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($sermoes)): ?>
                <?php foreach ($sermoes as $sermao): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="relative">
                            <?php if (!empty($sermao['midias'])): ?>
                                <div class="swiper-container">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($sermao['midias'] as $midia): ?>
                                            <?php if ($midia['tipo_arquivo'] === 'imagem'): ?>
                                                <div class="swiper-slide">
                                                    <a href="/sermao/<?php echo $sermao['id']; ?>">
                                                        <img src="/<?php echo $midia['caminho_arquivo']; ?>"
                                                            alt="<?php echo htmlspecialchars($sermao['titulo']); ?>"
                                                            class="w-full h-64 object-cover">
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            <?php else: ?>
                                <a href="/sermao/<?php echo $sermao['id']; ?>">
                                    <img src="/assets/img/placeholder-400x250.png"
                                        alt="<?php echo htmlspecialchars($sermao['titulo']); ?>" class="w-full h-64 object-cover">
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="p-6">
                            <a href="/sermao/<?php echo $sermao['id']; ?>"
                                class="text-xl font-bold hover:text-yellow-500 transition mb-3 block">
                                <?php echo htmlspecialchars($sermao['titulo']); ?>
                            </a>

                            <ul class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                                <?php if (!empty($sermao['data'])): ?>
                                    <li class="flex items-center">
                                        <i class="far fa-calendar text-yellow-500 mr-1"></i>
                                        <span><?php echo date("d.m.y", strtotime($sermao['data'])); ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if (!empty($sermao['pregador'])): ?>
                                    <li class="flex items-center">
                                        <i class="fas fa-user text-yellow-500 mr-1"></i>
                                        <span><?php echo htmlspecialchars($sermao['pregador']); ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>

                            <p class="text-gray-600 mb-4">
                                <?php echo substr(strip_tags($sermao['conteudo']), 0, 220); ?>...
                            </p>

                            <a href="/sermao/<?php echo $sermao['id']; ?>"
                                class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded transition">
                                Ver mais
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col-span-3 text-center text-gray-600">Nenhum sermão disponível no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../../Shared/Views/layout_public.php";
?>