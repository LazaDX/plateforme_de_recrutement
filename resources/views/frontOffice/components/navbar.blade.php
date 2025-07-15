<nav class="bg-white shadow p-4 flex justify-between items-center">
    <div class="text-xl font-bold">Plateforme Enquêteur</div>
    @auth
        <div>
            Bonjour <span class="font-semibold">{{ Auth::user()->nom }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline ml-4">
                @csrf
                <button class="text-red-500">Déconnexion</button>
            </form>
        </div>
    @endauth
</nav>
