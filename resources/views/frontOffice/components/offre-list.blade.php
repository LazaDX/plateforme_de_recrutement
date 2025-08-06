{{-- @foreach ($offres as $offre)
    @include('frontOffice.components.offre-card', ['offre' => $offre])
@endforeach --}}

@php
    use Carbon\Carbon;
    Carbon::setLocale('fr');
@endphp

<div
    class="bg-white border rounded-lg hover:shadow-lg transition-all duration-300 shadow p-6 mb-4 flex items-center justify-between">
    <div>
        <a href="{{ route('enqueteur.offre.show', $offre->id) }}"
            class="text-lg font-bold text-blue-900">{{ $offre->nom_enquete }}</a>
        <div class="flex items-center text-sm text-gray-500 mt-2 gap-4">
        </div>
        {{-- <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($offre->details_enquete, 200) }}</p> --}}
        <div class="flex items-center text-xs text-gray-500 mt-4 gap-4">
            <span> Publié le
                {{ \Carbon\Carbon::parse($offre->created_at)->isoFormat('D MMMM YYYY') }}</span>
        </div>
    </div>
</div>
