{{-- @foreach ($offres as $offre)
    @include('frontOffice.components.offre-card', ['offre' => $offre])
@endforeach --}}

<div
    class="bg-white border rounded-lg hover:shadow-lg transition-all duration-300 shadow p-6 mb-4 flex items-center justify-between">
    <div>
        <a href="{{ route('enqueteur.offre.show', $offre->id) }}"
            class="text-lg font-bold text-blue-900">{{ $offre->nom_enquete }}</a>
        <div class="flex items-center text-sm text-gray-500 mt-2 gap-4">
            <span><i class="fa fa-map-marker-alt mr-1"></i> {{ $offre->region->nom_region ?? 'Lieu inconnu' }}</span>
            <span><i class="fa fa-calendar-alt mr-1"></i>
                {{ \Carbon\Carbon::parse($offre->date_limite)->format('d/m/Y') }}</span>
        </div>
        <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($offre->details_enquete, 100) }}</p>

    </div>
</div>
