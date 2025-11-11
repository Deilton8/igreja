<div class="py-16 bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="title-font text-3xl md:text-4xl font-bold text-yellow-900 mb-4">Publicações Recentes</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Leia nossas últimas publicações e reflexões
            </p>
            <div class="w-20 h-1 bg-yellow-600 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($publicacoes)): ?>
                <?php foreach ($publicacoes as $pub): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="relative">
                            <?php if (!empty($pub['midias'])): ?>
                                <div class="swiper-container">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($pub['midias'] as $midia): ?>
                                            <?php if ($midia['tipo_arquivo'] === 'imagem'): ?>
                                                <div class="swiper-slide">
                                                    <a href="/publicacao/<?php echo $pub['id']; ?>">
                                                        <img src="/<?php echo $midia['caminho_arquivo']; ?>"
                                                            alt="<?php echo htmlspecialchars($pub['titulo']); ?>"
                                                            class="w-full h-64 object-cover">
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            <?php else: ?>
                                <a href="/publicacao/<?php echo $pub['id']; ?>">
                                    <img src="/assets/img/placeholder-400x250.png"
                                        alt="<?php echo htmlspecialchars($pub['titulo']); ?>" class="w-full h-64 object-cover">
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="p-6">
                            <a href="/publicacao/<?php echo $pub['id']; ?>"
                                class="text-xl font-bold hover:text-yellow-500 transition mb-3 block">
                                <?php echo htmlspecialchars($pub['titulo']); ?>
                            </a>

                            <ul class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                                <?php if (!empty($pub['publicado_em'])): ?>
                                    <li class="flex items-center">
                                        <i class="far fa-calendar text-yellow-500 mr-1"></i>
                                        <span><?php echo date("d.m.y H:i", strtotime($pub['publicado_em'])); ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if (!empty($pub['categoria'])): ?>
                                    <li class="flex items-center">
                                        <i class="fas fa-tag text-yellow-500 mr-1"></i>
                                        <span><?php echo htmlspecialchars($pub['categoria']); ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>

                            <p class="text-gray-600 mb-4">
                                <?php echo substr(strip_tags($pub['conteudo']), 0, 220); ?>...
                            </p>

                            <a href="/publicacao/<?php echo $pub['id']; ?>"
                                class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded transition">
                                Ver mais
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col-span-3 text-center text-gray-600">Nenhuma publicação disponível no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</div>