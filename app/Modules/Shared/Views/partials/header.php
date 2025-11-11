<!-- ================> Header section start here <================== -->
<header class="header" x-data="{ mobileMenuOpen: false, sobreOpen: false, blogOpen: false }">
    <!-- Top Header -->
    <div class="bg-white text-black py-2">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex flex-col md:flex-grid items-center space-x-4 mb-2 md:mb-0">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-phone-alt text-yellow-500"></i>
                        <span>+258 84 000 0000</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-yellow-500 mr-2"></i>
                        <span>info@imgd.org.mz</span>
                    </div>
                </div>

                <div class="hidden md:block">
                    <a href="/" class="text-2xl font-bold hover:text-yellow-500">IMGD</a>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex space-x-2">
                        <a href="#"
                            class="w-8 h-8 rounded-full flex items-center justify-center hover:text-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#"
                            class="w-8 h-8 rounded-full flex items-center justify-center hover:text-red-600 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#"
                            class="w-8 h-8 rounded-full flex items-center justify-center hover:text-pink-600 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <div id="main-nav" class="bg-black shadow-md transition-all duration-300 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-yellow-500">IMGD</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white focus:outline-none">
                        <i :class="mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-xl"></i>
                    </button>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <!-- Sobre Dropdown -->
                    <div class="relative" @mouseenter="sobreOpen = true" @mouseleave="sobreOpen = false">
                        <button @click="sobreOpen = !sobreOpen"
                            class="text-white hover:text-yellow-500 font-medium py-2 flex items-center space-x-1">
                            <span>Sobre <i class="fas fa-chevron-down text-sm"></i></span>
                        </button>
                        <div x-show="sobreOpen" x-transition
                            class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 z-10">
                            <a href="/testamento-fe"
                                class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-500">Testamento
                                de Fé</a>
                            <a href="/sobre-imgd"
                                class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-500">IMGD</a>
                            <a href="/sobre-ap-jeque"
                                class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-500">AP.
                                Jeque</a>
                            <a href="/sobre-acao-social"
                                class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-500">Acção
                                Social</a>
                        </div>
                    </div>

                    <a href="/eventos" class="text-white hover:text-yellow-500 font-medium py-2">Eventos</a>
                    <a href="/sermoes" class="text-white hover:text-yellow-500 font-medium py-2">Sermões</a>
                    <a href="/publicacoes" class="text-white hover:text-yellow-500 font-medium py-2">Publicações</a>
                    <a href="/contacto" class="text-white hover:text-yellow-500 font-medium py-2">Contacto</a>
                </nav>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-black text-white py-4">
                <ul class="flex flex-col space-y-2">
                    <li class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full text-left flex justify-between items-center px-4 py-2 hover:bg-gray-800">
                            Sobre
                            <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                        </button>
                        <ul x-show="open" x-transition class="pl-4 mt-2 space-y-1">
                            <li><a href="/testamento-fe" class="block px-4 py-2 hover:bg-gray-700">Testamento de Fé</a>
                            </li>
                            <li><a href="/sobre-imgd" class="block px-4 py-2 hover:bg-gray-700">IMGD</a></li>
                            <li><a href="/sobre-ap-jeque" class="block px-4 py-2 hover:bg-gray-700">AP. Jeque</a></li>
                            <li><a href="/sobre-acao-social" class="block px-4 py-2 hover:bg-gray-700">Acção Social</a>
                            </li>
                        </ul>
                    </li>

                    <li><a href="/eventos" class="block px-4 py-2 hover:bg-gray-800">Eventos</a></li>
                    <li><a href="/sermoes" class="block px-4 py-2 hover:bg-gray-800">Sermões</a></li>
                    <li><a href="/publicacoes" class="block px-4 py-2 hover:bg-gray-800">Publicações</a></li>
                    <li><a href="/contacto" class="block px-4 py-2 hover:bg-gray-800">Contacto</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<!-- ================> Header section end here <================== -->

<script>
    // Sticky Main Navigation
    document.addEventListener('DOMContentLoaded', function () {
        const nav = document.getElementById('main-nav');
        const offsetTop = nav.offsetTop;

        window.addEventListener('scroll', () => {
            if (window.scrollY > offsetTop) {
                nav.classList.add('fixed', 'top-0', 'left-0', 'right-0');
            } else {
                nav.classList.remove('fixed', 'top-0', 'left-0', 'right-0');
            }
        });
    });
</script>