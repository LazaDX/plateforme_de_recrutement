<header class="sticky top-0 z-40 bg-white border-b border-navy-200 shadow-sm px-6 py-3.5 backdrop-blur bg-opacity-95">
    <div class="flex items-center justify-between">
        <!-- Left: Menu (mobile) -->
        <div class="flex items-center">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-navy-50 focus:outline-none"
                title="Menu" aria-label="Menu">
                <svg class="h-5 w-5 text-navy-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Right: Profile -->
        <div class="relative flex items-center space-x-3" x-ref="dropdownRef" x-data="{ isDropdownOpen: false }">
            <button @click="isDropdownOpen = !isDropdownOpen" class="flex items-center space-x-2 rounded-lg"
                title="Profil" aria-haspopup="true" :aria-expanded="isDropdownOpen">
                <div class="text-right">
                    <p class="text-sm font-medium text-navy-900">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-xs text-navy-600 capitalize">
                        {{ Auth::user()->role }}
                    </p>
                </div>
                <div class="w-8 h-8 bg-navy-100 rounded-full flex items-center justify-center">
                    <span class="text-navy-600 font-semibold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                </div>
                <svg class="h-4 w-4 text-gray-600 hidden md:block transition-transform duration-200"
                    :class="{ 'rotate-180': isDropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div x-show="isDropdownOpen" @click.away="isDropdownOpen = false"
                class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border border-gray-100"
                style="display: none;">
                <a href="{{ route('admin.profile') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                    @click="isDropdownOpen = false">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Mon profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-50"
                        @click="isDropdownOpen = false">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
