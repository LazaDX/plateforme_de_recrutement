<div id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 shadow-lg">
    <div class="flex-shrink-0">
        <div class="flex items-center justify-between p-2 border-b border-gray-200">
            <div class="flex items-center">
                <img src="{{ asset('img/instat-logo.png') }}" class="h-12 pl-4 w-auto" alt="INSTAT Logo">
            </div>
            <button id="closeButton" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-times h-5 w-5 text-gray-600"></i>
            </button>
        </div>
    </div>
    <div class="flex-1 overflow-y-auto py-4">
        <nav class="px-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-tachometer-alt mr-3 h-5 w-5"></i>
                <span>Tableau de bord</span>
            </a>

            <a href="{{ route('admin.offers') }}"
                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.offers') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-briefcase mr-3 h-5 w-5"></i>
                <span>Offres d'enquêtes</span>
            </a>

            <a href="{{ route('admin.enqueteurs') }}"
                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.enqueteurs') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-users mr-3 h-5 w-5"></i>
                <span>Enquêteurs</span>
            </a>

            <a href="{{ route('admin.analytiques') }}"
                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.analytiques') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-chart-bar mr-3 h-5 w-5"></i>
                <span>Analytiques</span>
            </a>

            <a href="{{ route('admin.administrateurs') }}"
                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.administrateurs') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-user-shield mr-3 h-5 w-5"></i>
                <span>Administrateurs</span>
            </a>
        </nav>
    </div>
    <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-gray-50">
        <div class="relative">
            <button id="profileButton"
                class="w-full flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200">
                <div class="relative flex-shrink-0">
                    <img class="w-10 h-10 rounded-full object-cover border-2 border-gray-200"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()->nom) }}&background=6366f1&color=fff&size=128"
                        alt="Admin Avatar">
                    <span
                        class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white shadow-sm"></span>
                </div>
                <div class="flex-1 text-left min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::guard('admin')->user()->nom }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::guard('admin')->user()->role->nom_role }}</p>
                </div>
                <i class="fas fa-chevron-up text-gray-400 text-xs transform transition-transform duration-200"></i>
            </button>
            <div id="profileDropdown"
                class="hidden absolute bottom-full left-0 right-0 mb-2 bg-white shadow-lg rounded-lg border border-gray-200 py-1 z-50">
                <div class="px-4 py-3 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <img class="w-8 h-8 rounded-full object-cover"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()->nom) }}&background=6366f1&color=fff&size=128"
                            alt="Admin Avatar">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ Auth::guard('admin')->user()->nom }}</p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ Auth::guard('admin')->user()->email ?? 'admin@instat.mg' }}</p>
                        </div>
                    </div>
                </div>
                <div class="py-1">
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user-cog mr-3 h-4 w-4 text-gray-400"></i>
                        <span>Mon profil</span>
                    </a>
                    {{-- <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-cog mr-3 h-4 w-4 text-gray-400"></i>
                        <span>Paramètres</span>
                    </a> --}}
                    <div class="border-t border-gray-100 my-1"></div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="block">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors focus:outline-none">
                            <i class="fas fa-sign-out-alt mr-3 h-4 w-4"></i>
                            <span>Se déconnecter</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
