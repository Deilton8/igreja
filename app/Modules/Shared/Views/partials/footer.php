<?php
use App\Modules\Publication\Models\Publication;

// Garante que a variável $recentPosts existe
if (!isset($recentPosts) || !is_array($recentPosts)) {
    $publicationModel = new Publication();
    $allPosts = $publicationModel->all();

    // Pega os 3 últimos posts completos com mídias
    $recentPosts = [];
    foreach (array_slice($allPosts, 0, 3) as $p) {
        $recentPosts[] = $publicationModel->findWithMedia($p['id']);
    }
}
?>

<!-- ================> Social section start here <================== -->
<div class="bg-white py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-center">
            <ul class="flex space-x-6">
                <li>
                    <a href="#" class="flex items-center text-black hover:text-blue-700 transition">
                        <i class="fab fa-facebook-f mr-2"></i>
                        <span>facebook</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center text-black hover:text-red-600 transition">
                        <i class="fab fa-youtube mr-2"></i>
                        <span>youtube</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center text-black hover:text-pink-600 transition">
                        <i class="fab fa-instagram mr-2"></i>
                        <span>instagram</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- ================> Social section end here <================== -->

<!-- ================> Footer section start here <================== -->
<footer class="footer bg-black text-white">
    <div class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-8 text-center">
                <!-- About -->
                <div>
                    <h3 class="text-xl text-yellow-500 font-bold mb-6">Sobre IMGD</h3>
                    <div class="mb-4">
                        <img src="https://via.placeholder.com/300x200" alt="footer about" class="w-full rounded-md">
                    </div>
                    <p class="text-gray-400">Dramatically strategize economically sound action items for e-business
                        niches. Quickly re-engineer 24/365 potentialities before.</p>
                </div>

                <!-- Recent Posts -->
                <div>
                    <h3 class="text-xl text-yellow-500 font-bold mb-6">Publicações Recentes</h3>
                    <div class="space-y-4">
                        <?php if (!empty($recentPosts)): ?>
                            <?php foreach ($recentPosts as $post): ?>
                                <?php
                                // Thumbnail: primeira mídia imagem ou placeholder
                                $thumb = '/assets/img/placeholder-100x100.png';
                                if (!empty($post['midias'][0]['caminho_arquivo'])) {
                                    $thumb = '/' . ltrim($post['midias'][0]['caminho_arquivo'], '/');
                                }
                                $published = !empty($post['publicado_em']) ? date("d M, Y", strtotime($post['publicado_em'])) : '';
                                ?>
                                <div class="flex justify-center">
                                    <div class="w-16 h-16 flex-shrink-0 mr-4">
                                        <a href="/publicacao/<?php echo $post['id']; ?>">
                                            <img src="<?php echo htmlspecialchars($thumb); ?>" alt="post"
                                                class="w-full h-full object-cover rounded-md">
                                        </a>
                                    </div>
                                    <div>
                                        <a href="/publicacao/<?php echo $post['id']; ?>"
                                            class="font-medium hover:text-yellow-500 transition">
                                            <?php echo htmlspecialchars($post['titulo']); ?>
                                        </a>
                                        <?php if ($published): ?>
                                            <p class="text-gray-400 text-sm mt-1">
                                                <i class="far fa-calendar-alt mr-1"></i> <?php echo $published; ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-400">Nenhuma publicação disponível no momento.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Useful Links -->
                <div>
                    <h3 class="text-xl text-yellow-500 font-bold mb-6">Links Úteis</h3>
                    <ul class="space-y-4">
                        <li><a href="/sobre-imgd" class="text-white hover:text-yellow-500 transition">Sobre IMGD</a>
                        </li>
                        <li><a href="/sobre-ap-jeque" class="text-white hover:text-yellow-500 transition">Sobre Apostolo
                                Jeque</a></li>
                        <li><a href="/sobre-acao-social" class="text-white hover:text-yellow-500 transition">Sobre Acção
                                Social</a></li>
                        <li><a href="/eventos" class="text-white hover:text-yellow-500 transition">Eventos</a></li>
                        <li><a href="/mensagens" class="text-white hover:text-yellow-500 transition">Mensagens</a></li>
                        <li><a href="/contacto" class="text-white hover:text-yellow-500 transition">Contacto</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white py-6">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <a href="/" class="text-2xl font-bold text-yellow-500 mb-4 inline-block">IMGD</a>
                <p class="text-gray-400">&copy; <?php echo date('Y'); ?> <a href="/"
                        class="text-yellow-500 hover:underline">Igreja Ministério da Graça de Deus</a> - Todos os
                    direitos reservados.</p>
            </div>
        </div>
    </div>
</footer>
<!-- ================> Footer section end here <================== -->