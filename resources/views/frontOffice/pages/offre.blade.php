@php
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('frontOffice.layouts.app')

@section('title', "Offres d'enquête")

@section('content')
    <div x-data="{
        viewMode: '{{ request('view', 'list') }}',
        isUrgent(deadline) {
            const deadlineDate = new Date(deadline);
            const today = new Date();
            const diffTime = deadlineDate.getTime() - today.getTime();
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays <= 3;
        },
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('fr-FR', { year: 'numeric', month: 'short', day: 'numeric' });
        },
        getDaysUntilDeadline(deadline) {
            const deadlineDate = new Date(deadline);
            const today = new Date();
            const diffTime = deadlineDate.getTime() - today.getTime();
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }
    }">
        <section class="bg-gradient-to-r from-blue-900 to-green-600 text-white py-16 md:py-20 relative">
            <div class="absolute inset-0 bg-cover bg-center opacity-20"
                style="background-image: url('https://images.unsplash.com/photo-1521791136064-7986c2920216?...');"></div>

            <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-6">Rejoignez l'équipe de l'INSTAT Madagascar</h1>
                <p class="text-lg mb-8 opacity-90">Contribuez au développement statistique du pays</p>

                <form method="GET" x-data="{ search: '{{ request('search') }}' }" action="{{ route('enqueteur.offre') }}" class="max-w-2xl mx-auto"
                    x-data x-init="document.querySelector('input[name=search]').value = ''">
                    <div class="relative flex items-center bg-white rounded-full shadow-lg p-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher un poste..."
                            class="w-full pl-12 pr-4 py-4 text-gray-900 rounded-3xl focus:outline-none" />
                        <div class="absolute right-1.5">
                            <button type="submit" class="px-8 py-4 rounded-3xl shadow-md bg-blue-600 text-white">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>


        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <div class="lg:col-span-3">
                    @if ($search)
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                            <h2 class="text-xl font-sans text-gray-500 mb-4">
                                Résultats pour : <span class="font-bold">"{{ $search }}"</span>
                            </h2>
                        </div>

                        @if ($offres->isNotEmpty())
                            @foreach ($offres as $offre)
                                @include('frontOffice.components.offre-list', ['offre' => $offre])
                            @endforeach
                        @else
                            <div class="bg-white p-12 text-center rounded-lg shadow">
                                <i class="fas fa-briefcase text-gray-400 text-3xl mb-4"></i>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Aucune offre trouvée</h3>
                                <p class="text-gray-600">Essayez d'ajuster vos critères de recherche.</p>
                            </div>
                        @endif
                    @else
                        <form method="GET" action="{{ route('enqueteur.offre') }}"
                            class="bg-white rounded-lg shadow-sm p-6 mb-8">
                            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                                <div class="text-2xl font-semibold font-light text-gray-600"> ({{ $offres->count() }}
                                    {{ Str::plural('offre', $offres->count()) }}
                                    d'enquête{{ $offres->count() > 1 ? 's' : '' }}
                                    trouvée{{ $offres->count() > 1 ? 's' : '' }})
                                    à l’Institut Statistique de Madagascar
                                </div>
                                {{-- <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-800">Affichage :</span>
                                    <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                                        <button type="submit" name="view" value="list"
                                            class="px-3 py-2 {{ request('view', 'list') === 'list' ? 'bg-blue-100 text-blue-700' : 'text-gray-600' }}">
                                            <i class="fas fa-list"></i>
                                        </button>
                                        <button type="submit" name="view" value="card"
                                            class="px-3 py-2 {{ request('view') === 'card' ? 'bg-blue-100 text-blue-700' : 'text-gray-600' }}">
                                            <i class="fas fa-th-large"></i>
                                        </button>
                                    </div>
                                </div> --}}
                            </div>
                        </form>
                        @if ($viewMode === 'list')
                            @foreach ($offres as $offre)
                                @include('frontOffice.components.offre-list', ['offre' => $offre])
                            @endforeach
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($offres as $offre)
                                    @include('frontOffice.components.offre-card', ['offre' => $offre])
                                @endforeach
                            </div>
                        @endif
                        @if ($offres->isEmpty())
                            <div class="bg-white p-12 text-center rounded-lg shadow">
                                <i class="fas fa-briefcase text-gray-400 text-3xl mb-4"></i>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Aucune offre trouvée</h3>
                                <p class="text-gray-600">Essayez d'ajuster vos critères de recherche.</p>
                            </div>
                        @endif
                        <div class="mt-8">
                            {{ $offres->links() }}
                        </div>
                    @endif
                </div>
                <aside class="lg:col-span-1">
                    @include('frontOffice.components.sidebar-content')
                </aside>
            </div>
        </section>
    </div>
@endsection
