<section id="contact" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <!-- Título -->
        <div class="text-center mb-12">
            <h2 class="title-font text-3xl md:text-4xl font-bold text-yellow-900 mb-4">Entre em Contacto</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Estamos aqui para responder às suas perguntas e orar por você
            </p>
            <div class="w-20 h-1 bg-yellow-600 mx-auto mt-4"></div>
        </div>

        <!-- Formulário e Informações -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Formulário -->
            <div class="lg:w-1/2">
                <form method="POST" action="?url=contact" class="bg-white p-6 rounded-lg shadow-md space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Nome</label>
                        <input name="name" type="text" required
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            placeholder="Seu nome">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Email</label>
                        <input name="email" type="email" required
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            placeholder="seu@email.com">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Assunto</label>
                        <select name="subject" required
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="">Selecione um assunto</option>
                            <option value="Testemunho">Testemunho</option>
                            <option value="Oração">Oração</option>
                            <option value="Informações">Informações</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Mensagem</label>
                        <textarea name="message" rows="4" required
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            placeholder="Sua mensagem"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-yellow-600 hover:bg-yellow-900 text-white py-3 rounded-md font-medium transition flex items-center justify-center">
                        Enviar Mensagem <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </form>
            </div>

            <!-- Informações de contacto -->
            <div class="lg:w-1/2">
                <div class="bg-white p-6 rounded-lg shadow-md h-full space-y-6">
                    <h3 class="text-xl font-bold text-yellow-900 mb-4">Informações de Contacto</h3>

                    <div>
                        <h4 class="font-bold text-gray-700 mb-2">Endereço</h4>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-yellow-600 mt-1"></i>
                            <p>Av. Joaquim Chissano, nº 58, <br> Bairro da Matola H, Matola, Moçambique</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-700 mb-2">Horários de Culto</h4>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-clock text-yellow-600 mt-1"></i>
                            <div>
                                <p class="font-medium">Domingo: 09h:00</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-clock text-yellow-600 mt-1"></i>
                            <div>
                                <p class="font-medium">Quarta-feira: 18h:00</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-clock text-yellow-600 mt-1"></i>
                            <div>
                                <p class="font-medium">Sexta-feira: 18h:00</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-700 mb-2">Contactos</h4>
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fas fa-phone text-yellow-600"></i>
                            <p>+258 84 000 0000</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-yellow-600"></i>
                            <p>contato@imgd.org.mz</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-700 mb-2">Redes Sociais</h4>
                        <div class="flex space-x-4">
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-blue-800 text-white flex items-center justify-center hover:bg-blue-700">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-pink-600 text-white flex items-center justify-center hover:bg-pink-700">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-700">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>