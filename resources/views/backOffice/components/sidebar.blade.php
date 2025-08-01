<!-- resources/views/backOffice/components/sidebar.blade.php -->
<div x-data="{ open: window.innerWidth >= 1024 }" x-init="open = window.innerWidth >= 1024" @resize.window="open = window.innerWidth >= 1024"
    x-show="open || window.innerWidth >= 1024" x-transition:enter="transition-transform ease-in-out duration-300"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition-transform ease-in-out duration-300" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform flex flex-col"
    :class="{ 'translate-x-0': open, '-translate-x-full': !open }"
    @click.outside="if(window.innerWidth < 1024) open = false" x-cloak>

    <div class="flex-1 overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div>
                <img src="{{ asset('img/instat-logo.png') }}" class="h-14 px-4" alt="">
            </div>
            <button @click="open = false" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-times h-5 w-5 text-gray-600"></i>
            </button>
        </div>

        <nav class="p-4 mt-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-tachometer-alt mr-3 h-5 w-5"></i>
                Tableau de bord
            </a>

            <a href="{{ route('admin.offers') }}"
                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.offers') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-briefcase mr-3 h-5 w-5"></i>
                Offres d'enquêtes
            </a>

            <a href="{{ route('admin.enqueteurs') }}"
                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.enqueteurs') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-users mr-3 h-5 w-5"></i>
                Enquêteurs
            </a>

            <a href="{{ route('admin.analytiques') }}"
                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.analytiques') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-chart-bar mr-3 h-5 w-5"></i>
                Analytiques
            </a>

            <a href="{{ route('admin.historiques') }}"
                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.historiques') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-history mr-3 h-5 w-5"></i>
                Historique d'activité
            </a>
        </nav>
    </div>

    <!-- Profil Admin -->
    <div class="p-4 border-t border-gray-200 ">
        <div class="flex items-center space-x-3">
            <div class="relative">
                <img class="w-10 h-10 rounded-full object-cover"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()->nom) }}&background=random"
                    alt="Admin Avatar">
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('admin')->user()->nom }}</p>
                <p class="text-xs text-gray-500">Administrateur</p>
            </div>
        </div>
        <div class="mt-3">
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="text-xs text-gray-500 hover:text-gray-700 flex items-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>
</div>
