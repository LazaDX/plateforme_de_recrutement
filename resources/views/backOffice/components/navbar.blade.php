<nav class="bg-white shadow p-4 flex justify-between items-center">
    <div class="text-xl font-bold">Plateforme Admin</div>
    @auth('admin')
        <div>
            Bonjour <span class="font-semibold">{{ Auth::guard('admin')->user()->nom }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-500 hover:underline">Se déconnecter</button>
            </form>
        </div>
    @endauth
</nav>
