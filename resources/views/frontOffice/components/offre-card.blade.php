<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-xl font-semibold text-blue-900 mb-2">{{ $offre->nom_enquete }}</h3>
        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-4">
            <span class="flex items-center">
                <i class="fa fa-map-marker-alt mr-1"></i> {{ $offre->region->nom_region ?? 'Lieu non défini' }}
            </span>
            <span class="flex items-center">
                <i class="fa fa-clock mr-1"></i> {{ $offre->type_contrat ?? 'Type inconnu' }}
            </span>
            <span class="flex items-center">
                <i class="fa fa-calendar-alt mr-1"></i>
                {{ $offre->date_limite ? \Carbon\Carbon::parse($offre->date_limite)->format('d/m/Y') : 'Non précisée' }}
            </span>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-gray-200 rounded-full mr-3"></div>
            <span class="font-medium">INSTAT</span>
        </div>
        <p class="text-gray-600 mb-4 line-clamp-3">{{ \Illuminate\Support\Str::limit($offre->details_enquete, 250) }}
        </p>
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach ($offre->tags ?? [] as $tag)
                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">{{ $tag }}</span>
            @endforeach
        </div>
    </div>
    <div class="p-4 flex justify-between items-center">
        <span class="font-medium text-green-600">{{ $offre->salaire ?? 'Selon profil' }}</span>
        <a href="{{ route('enqueteur.postule', $offre->id) }}"
            class="text-blue-600 hover:text-blue-800 font-semibold">Voir plus</a>
    </div>
</div>
