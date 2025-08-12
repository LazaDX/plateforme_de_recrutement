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
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-blue-800 to-green-700 text-white py-20 md:py-24 relative overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center opacity-10"
                style="background-image: url('https://images.unsplash.com/photo-1521791136064-7986c2920216?...');">
            </div>

            <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Opportunités d'enquête à l'INSTAT</h1>
                    <p class="text-xl mb-8 opacity-90">Contribuez au développement statistique national et façonnez l'avenir
                        de Madagascar</p>

                    <div class="max-w-2xl mx-auto">
                        <form method="GET" x-data="{ search: '{{ request('search') }}' }" action="{{ route('enqueteur.offre') }}">
                            <div class="relative flex items-center bg-white rounded-full shadow-xl">
                                <div class="absolute left-5 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Rechercher un poste, une localisation ou un domaine..."
                                    class="w-full pl-14 pr-6 py-4 text-gray-900 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                <button type="submit"
                                    class="absolute right-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full px-6 py-3 transition duration-200 shadow-md">
                                    <span class="hidden md:inline">Rechercher</span>
                                    <span class="md:hidden"><i class="fas fa-search"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm">Enquête ménage</span>
                        <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm">Recensement</span>
                        <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm">Statistiques économiques</span>
                        <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm">Analyste données</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Main Content Area -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Search Results Header -->
                    @if ($search)
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-700 mb-1">
                                        Résultats de recherche pour : <span
                                            class="text-blue-600">"{{ $search }}"</span>
                                    </h2>
                                    <p class="text-gray-500 text-sm">{{ $offres->total() }} résultat(s) trouvé(s)</p>
                                </div>
                                <a href="{{ route('enqueteur.offre') }}"
                                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                    <i class="fas fa-times mr-1"></i> Effacer la recherche
                                </a>
                            </div>
                        </div>

                        @if ($offres->isNotEmpty())
                            <div class="space-y-6">
                                @foreach ($offres as $offre)
                                    @include('frontOffice.components.offre-list', ['offre' => $offre])
                                @endforeach
                            </div>

                            <div class="mt-8">
                                {{ $offres->links() }}
                            </div>
                        @else
                            <div class="bg-white p-12 text-center rounded-lg shadow-sm border border-gray-100">
                                <div
                                    class="mx-auto w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                    <i class="fas fa-search text-blue-400 text-3xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">Aucun résultat trouvé</h3>
                                <p class="text-gray-600 max-w-md mx-auto mb-6">Nous n'avons trouvé aucune offre
                                    correspondant à votre recherche. Essayez avec d'autres termes.</p>
                                <a href="{{ route('enqueteur.offre') }}"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i> Voir toutes les offres
                                </a>
                            </div>
                        @endif
                    @else
                        <!-- All Offers Section -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-700">Offres d'enquête disponibles</h2>
                                    <p class="text-gray-500 text-sm mt-1">
                                        {{ $offres->total() }} offre(s) actuellement - {{ $offres->count() }} affichée(s)
                                        sur cette page
                                    </p>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600 hidden md:block">Affichage :</span>
                                    <div class="inline-flex rounded-md shadow-sm">
                                        <a href="?view=list"
                                            class="px-4 py-2 text-sm font-medium rounded-l-lg border border-gray-300 {{ request('view', 'list') === 'list' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                                            <i class="fas fa-list mr-2"></i> Liste
                                        </a>
                                        <a href="?view=card"
                                            class="px-4 py-2 text-sm font-medium rounded-r-lg border border-gray-300 {{ request('view') === 'card' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                                            <i class="fas fa-th-large mr-2"></i> Grille
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Offers Display -->
                        @if ($viewMode === 'list')
                            <div class="space-y-6">
                                @foreach ($offres as $offre)
                                    @include('frontOffice.components.offre-list', ['offre' => $offre])
                                @endforeach
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($offres as $offre)
                                    @include('frontOffice.components.offre-card', ['offre' => $offre])
                                @endforeach
                            </div>
                        @endif

                        @if ($offres->isEmpty())
                            <div class="bg-white p-12 text-center rounded-lg shadow-sm border border-gray-100">
                                <div
                                    class="mx-auto w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                    <i class="fas fa-briefcase text-blue-400 text-3xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">Aucune offre disponible actuellement</h3>
                                <p class="text-gray-600 max-w-md mx-auto">Nous n'avons pas d'offres d'enquête pour le
                                    moment. Veuillez vérifier ultérieurement.</p>
                            </div>
                        @endif

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $offres->links() }}
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <aside class="lg:col-span-1 space-y-6">
                    @include('frontOffice.components.sidebar-content')
                    {{-- <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                        <h3 class="font-semibold text-lg text-blue-800 mb-3">Besoin d'aide ?</h3>
                        <p class="text-blue-700 mb-4">Contactez notre service recrutement pour toute question concernant les
                            offres.</p>
                        <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-envelope mr-2"></i> contact@instat.mg
                        </a>
                    </div> --}}
                </aside>
            </div>
        </section>
    </div>
@endsection
