<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-xl font-semibold text-blue-900 mb-2">{{ $offre->nom_enquete }}</h3>
        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-4">
            <span class="flex items-center">
                <i class="fa fa-calendar-alt mr-1"></i>
                {{ $offre->date_limite ? \Carbon\Carbon::parse($offre->date_limite)->format('d/m/Y') : 'Non précisée' }}
            </span>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-gray-200 rounded-full mr-3">
                <img src="{{ asset('img/instat-logo.png') }}" alt="">
            </div>
            <span class="font-medium">Intitut statistique Madagascar</span>
        </div>
    </div>
    <div class="p-4 flex justify-between items-center">
        <a href="{{ route('enqueteur.offre.show', $offre->id) }}"
            class="text-blue-600 hover:text-blue-800 font-semibold">Voir plus</a>
    </div>
</div>
