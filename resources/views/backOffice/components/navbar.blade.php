<header class="sticky top-0 z-30 bg-gray-50 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button id="burgerButton"
                    class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <i class="fas fa-bars h-5 w-5 text-gray-600"></i>
                </button>
                <div class="sm:block">
                    <h1 class="text-sm font-semibold text-gray-400">
                        @yield('page-title', 'Institut national de la statistique ')
                    </h1>
                </div>
            </div>
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- Bouton de recherche (optionnel) -->
                {{-- <button
                    class="hidden sm:flex p-2 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <i class="fas fa-search h-5 w-5 text-gray-600"></i>
                </button> --}}

                <!-- Messages -->
                {{-- <div class="relative">
                    <button id="messagesButton"
                        class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <i class="fas fa-envelope h-5 w-5 text-gray-600"></i>
                        <!-- Badge de notification -->
                        <span
                            class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-white"></span>
                    </button>

                    <!-- Messages dropdown (optionnel) -->
                    <div id="messagesDropdown"
                        class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50 border border-gray-100 py-1">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">Messages</p>
                                <span class="text-xs text-gray-500">3 nouveaux</span>
                            </div>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <a href="#"
                                class="flex items-start px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50">
                                <img class="w-8 h-8 rounded-full mr-3 flex-shrink-0"
                                    src="https://ui-avatars.com/api/?name=Enqueteur&background=random" alt="">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate">Enquêteur 1</p>
                                    <p class="text-gray-600 truncate">Rapport d'enquête soumis</p>
                                    <p class="text-xs text-gray-500 mt-1">Il y a 5 min</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-start px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                                <img class="w-8 h-8 rounded-full mr-3 flex-shrink-0"
                                    src="https://ui-avatars.com/api/?name=Enqueteur2&background=random" alt="">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate">Enquêteur 2</p>
                                    <p class="text-gray-600 truncate">Question sur l'enquête #123</p>
                                    <p class="text-xs text-gray-500 mt-1">Il y a 2 heures</p>
                                </div>
                            </a>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-100">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Voir tous
                                les messages</a>
                        </div>
                    </div>
                </div> --}}

                <!-- Notifications -->
                {{-- <div class="relative">
                    <button id="notificationsButton"
                        class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <i class="fas fa-bell h-5 w-5 text-gray-600"></i>
                        <!-- Badge de notification -->
                        <span
                            class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                    </button>

                    <!-- Notifications dropdown -->
                    <div id="notificationsDropdown"
                        class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50 border border-gray-100 py-1">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">Notifications</p>
                                <span class="text-xs text-gray-500">2 nouvelles</span>
                            </div>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <a href="#"
                                class="flex items-start px-4 py-3 text-sm hover:bg-gray-50 border-b border-gray-50">
                                <div
                                    class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-gray-900 font-medium">Nouvelle enquête disponible</p>
                                    <p class="text-gray-600 text-xs">Enquête démographique 2024</p>
                                    <p class="text-xs text-gray-500 mt-1">Il y a 10 min</p>
                                </div>
                            </a>
                            <a href="#"
                                class="flex items-start px-4 py-3 text-sm hover:bg-gray-50 border-b border-gray-50">
                                <div
                                    class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-file-alt text-blue-600 text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-gray-900 font-medium">Rapport soumis</p>
                                    <p class="text-gray-600 text-xs">Par l'enquêteur Jean Dupont</p>
                                    <p class="text-xs text-gray-500 mt-1">Il y a 1 heure</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-start px-4 py-3 text-sm text-gray-600 hover:bg-gray-50">
                                <div
                                    class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-exclamation text-yellow-600 text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-gray-900">Mise à jour système</p>
                                    <p class="text-gray-600 text-xs">Maintenance programmée ce soir</p>
                                    <p class="text-xs text-gray-500 mt-1">Il y a 3 heures</p>
                                </div>
                            </a>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-100">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Voir toutes
                                les notifications</a>
                        </div>
                    </div>
                </div> --}}
                <div class="hidden sm:flex items-center space-x-2">
                    <button
                        class="p-2 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200"
                        title="Mode plein écran">
                        <i class="fas fa-expand h-4 w-4 text-gray-600"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationsButton = document.getElementById('notificationsButton');
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        const messagesButton = document.getElementById('messagesButton');
        const messagesDropdown = document.getElementById('messagesDropdown');
        if (messagesButton && messagesDropdown) {
            messagesButton.addEventListener('click', function(e) {
                e.stopPropagation();
                if (notificationsDropdown) {
                    notificationsDropdown.classList.add('hidden');
                }
                messagesDropdown.classList.toggle('hidden');
            });
            messagesDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        const fullscreenButton = document.querySelector('[title="Mode plein écran"]');
        if (fullscreenButton) {
            fullscreenButton.addEventListener('click', function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                    this.innerHTML = '<i class="fas fa-compress h-4 w-4 text-gray-600"></i>';
                    this.title = 'Quitter le mode plein écran';
                } else {
                    document.exitFullscreen();
                    this.innerHTML = '<i class="fas fa-expand h-4 w-4 text-gray-600"></i>';
                    this.title = 'Mode plein écran';
                }
            });
        }
    });
</script>
