<nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-40">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo à gauche --}}
            <div class="flex-shrink-0">
                <img src="{{ asset('img/instat-logo.png') }}" alt="logo" class="h-12 cursor-pointer" />
            </div>

            {{-- Navigation au centre --}}
            <div class="hidden lg:flex flex-1 justify-center">
                <div class="flex space-x-8">
                    @foreach ([['name' => "Offre d'enquête", 'href' => '/enqueteur/offre'], ['name' => 'Mes candidatures', 'href' => '/enqueteur/candidatures'], ['name' => 'Tableau de bord', 'href' => '/enqueteur/dashboard']] as $item)
                        <a href="{{ $item['href'] }}"
                            class="relative px-3 py-2 text-sm font-medium group {{ request()->is(ltrim($item['href'], '/') . '*') ? 'text-blue-950' : 'text-gray-600 hover:text-green-900' }}">
                            {{ $item['name'] }}
                            <span
                                class="absolute bottom-0 left-3 h-0.5 transition-all duration-300
                        {{ request()->is(ltrim($item['href'], '/') . '*') ? 'w-10 bg-blue-600' : 'w-0 group-hover:w-10 group-hover:bg-gray-300' }}">
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
            <div id="navbarApp" class="relative flex-shrink-0 pl-4">
                @auth
                    <profile-dropdown>
                        <template #button>
                            @if (Auth::user()->photo)
                                <img src="{{ Auth::user()->photo }}" alt="{{ Auth::user()->nom }}"
                                    class="w-8 h-8 rounded-full object-cover" />
                            @else
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-right">
                                    <span class="text-gray-600 font-medium text-xs">
                                        {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <span class="hidden md:block text-sm font-medium text-gray-900">{{ Auth::user()->nom }}</span>
                        </template>

                        <template #menu>
                            <a href="/enqueteur/login"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user mr-2"></i>
                                Mon profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Se déconnecter
                                </button>
                            </form>
                        </template>
                    </profile-dropdown>
                @endauth
            </div>
        </div>
    </div>
</nav>
