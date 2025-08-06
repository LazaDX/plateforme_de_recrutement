<header class="sticky top-0 z-40 bg-white shadow-sm px-6 py-4 backdrop-blur bg-opacity-95">
    <div class="flex items-center justify-between">
        <!-- Left: Menu (mobile) -->
        <div class="flex items-center">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-navy-50 focus:outline-none"
                title="Menu" aria-label="Menu">
                <i class="fas fa-bars h-5 w-5 text-navy-600"></i>
            </button>
        </div>

        <!-- Right: Profile -->
        <div class="relative flex items-center space-x-3" x-ref="dropdownRef" x-data="{ isDropdownOpen: false }">
            @auth
                <button @click="isDropdownOpen = !isDropdownOpen" class="flex items-center space-x-2 rounded-lg"
                    title="Profil" aria-haspopup="true" :aria-expanded="isDropdownOpen">
                    <div class="text-right">
                        <p class="text-sm font-medium text-navy-900">
                            {{ Auth::guard('admin')->user()->nom }}
                        </p>
                        {{-- <p class="text-xs text-navy-600 capitalize">
                        {{ Auth::user()->role }}
                    </p> --}}
                    </div>
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                        <span class="text-navy-600 font-semibold text-sm">
                            {{ strtoupper(substr(Auth::guard('admin')->user()->nom, 0, 1)) }}
                        </span>
                    </div>
                    <i class="fas fa-chevron-down h-4 w-4 text-gray-600 hidden md:block transition-transform duration-200"
                        :class="{ 'transform rotate-180': isDropdownOpen }"></i>
                </button>

                <!-- Dropdown -->
                <div x-show="isDropdownOpen" @click.away="isDropdownOpen = false"
                    class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border border-gray-100"
                    style="display: none;">
                    <a href="{{ route('admin.profile') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        @click="isDropdownOpen = false">
                        <i class="fas fa-user mr-2 h-4 w-4"></i>
                        Mon profil
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-50"
                            @click="isDropdownOpen = false">
                            <i class="fas fa-sign-out-alt mr-2 h-4 w-4"></i>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">
                    Se connecter
                </a>
            @endauth
        </div>

    </div>
</header>
