<!-- resources/views/backOffice/components/sidebar.blade.php -->
<div x-data="{ open: window.innerWidth >= 1024 }" x-init="open = window.innerWidth >= 1024" @resize.window="open = window.innerWidth >= 1024"
    x-show="open || window.innerWidth >= 1024" x-transition:enter="transition-transform ease-in-out duration-300"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition-transform ease-in-out duration-300" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform"
    :class="{ 'translate-x-0': open, '-translate-x-full': !open }"
    @click.outside="if(window.innerWidth < 1024) open = false" x-cloak>

    <div class="flex items-center justify-between p-5 border-b border-gray-200">
        <div>
            <img src="{{ asset('img/instat-logo.png') }}" class="h-16 px-1" alt="">
        </div>
        <button @click="open = false" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
            <i class="fas fa-times h-5 w-5 text-gray-600"></i>
        </button>
    </div>

    <nav class="p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <i class="fas fa-tachometer-alt mr-3 h-5 w-5"></i>
            Tableau de bord
        </a>

        <a href="{{ route('admin.offers') }}"
            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.offers') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <i class="fas fa-briefcase mr-3 h-5 w-5"></i>
            Offres d'enquêtes
        </a>

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.enquirers') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <i class="fas fa-users mr-3 h-5 w-5"></i>
            Enquêteurs
        </a>
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.analytics') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <i class="fas fa-chart-bar mr-3 h-5 w-5"></i>
            Analytiques
        </a>

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.activity') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <i class="fas fa-history mr-3 h-5 w-5"></i>
            Historique d'activité
        </a>
    </nav>
</div>
