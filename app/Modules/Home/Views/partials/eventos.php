<div class="py-8">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="title-font text-3xl md:text-4xl font-bold text-yellow-900 mb-4">Próximos Eventos</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Junte-se a nós nestes momentos especiais de comunhão e crescimento
            </p>
            <div class="w-20 h-1 bg-yellow-600 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($eventos)): ?>
                <?php foreach ($eventos as $evento): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="relative">
                            <?php if (!empty($evento['midias'])): ?>
                                <div class="swiper-container">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($evento['midias'] as $midia): ?>
                                            <div class="swiper-slide">
                                                <a href="/evento/<?php echo $evento['id']; ?>">
                                                    <img src="/<?php echo $midia['caminho_arquivo']; ?>"
                                                        alt="<?php echo htmlspecialchars($evento['titulo']); ?>"
                                                        class="w-full h-64 object-cover">

                                                    <?php
                                                    $data = new DateTime($evento['data_inicio']);
                                                    ?>
                                                    <div
                                                        class="absolute top-4 left-4 text-yellow-500 text-center p-2 rounded-md border-2">
                                                        <h6 class="font-bold text-lg"><?php echo $data->format("d"); ?></h6>
                                                        <p class="text-sm"><?php echo $data->format("M"); ?></p>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="swiper-pagination"></div> <!-- Apenas indicadores -->
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="p-6">
                            <a href="/evento/<?php echo $evento['id']; ?>"
                                class="text-xl font-bold hover:text-yellow-500 transition">
                                <?php echo htmlspecialchars($evento['titulo']); ?>
                            </a>
                            <div class="my-4">
                                <ul class="space-y-2">
                                    <li class="flex items-center text-gray-600">
                                        <i class="far fa-calendar-alt text-yellow-500 mr-2"></i>
                                        <span>
                                            <?php echo date("d.m.y H:i", strtotime($evento['data_inicio'])); ?>
                                            <?php if (!empty($evento['data_fim'])): ?>
                                                - <?php echo date("d.m.y H:i", strtotime($evento['data_fim'])); ?>
                                            <?php endif; ?>
                                        </span>
                                    </li>
                                    <li class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt text-yellow-500 mr-2"></i>
                                        <span><?php echo htmlspecialchars($evento['local']); ?></span>
                                    </li>
                                </ul>
                            </div>
                            <p class="text-gray-600 mb-4">
                                <?php echo substr(strip_tags($evento['descricao']), 0, 500); ?>...
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col-span-3 text-center text-gray-600">Nenhum evento disponível no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</div>