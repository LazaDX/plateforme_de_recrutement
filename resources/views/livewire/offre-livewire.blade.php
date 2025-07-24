@php
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('frontOffice.layouts.app')

@section('title', "Offres d'enquête")

@section('content')
    <div x-data="{
        viewMode: @entangle('viewMode'),
        selectedOffer: @entangle('selectedOffer'),
        openModal: false,
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
        <!-- Hero Banner with Search -->
        <div class="bg-gradient-to-r from-blue-900 to-green-600 text-white py-16 md:py-20 relative">
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2069&q=80')] opacity-20 bg-cover bg-center">
            </div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-3xl md:text-4xl font-bold mb-6">Rejoignez l'équipe de l'INSTAT Madagascar</h1>
                    <p class="text-lg mb-8 opacity-90">Contribuez au développement statistique du pays et participez à des
                        projets nationaux importants</p>
                    <div class="max-w-2xl mx-auto bg-white rounded-full shadow-2xl p-1">
                        <div class="relative flex items-center">
                            <div class="absolute left-4 text-gray-400">
                                <i class="fas fa-search h-5 w-5"></i>
                            </div>
                            <input type="text" placeholder="Rechercher un poste, un département..."
                                wire:model.live="searchTerm"
                                class="w-full pl-12 pr-4 py-4 text-gray-900 rounded-3xl focus:outline-none" />
                            <div class="absolute right-1.5">
                                <button
                                    class="px-8 py-4 rounded-3xl shadow-md hover:shadow-lg transition-all bg-blue-600 text-white">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Left Column - Job Offers -->
                <div class="lg:col-span-3">
                    <!-- Search and Filters -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                            <div class="text-2xl font-light text-gray-600">Toutes les offres d'enquêtes de l'INSTAT
                                Madagascar</div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-800">Affichage :</span>
                                <div class="flex border border-gray-300 rounded-lg overflow-hidden border-none">
                                    <button @click="viewMode = 'list'"
                                        :class="{ 'bg-blue-100 text-blue-700': viewMode === 'list', 'bg-white text-gray-600': viewMode !== 'list' }"
                                        class="px-3 py-2">
                                        <i class="fas fa-list h-4 w-4"></i>
                                    </button>
                                    <button @click="viewMode = 'card'"
                                        :class="{ 'bg-blue-100 text-blue-700': viewMode === 'card', 'bg-white text-gray-600': viewMode !== 'card' }"
                                        class="px-3 py-2">
                                        <i class="fas fa-th-large h-4 w-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end space-x-3">
                            <span class="text-sm font-medium text-gray-700">
                                {{ $offres->count() }} poste{{ $offres->count() !== 1 ? 's' : '' }}
                                trouvé{{ $offres->count() !== 1 ? 's' : '' }}
                            </span>
                            <button wire:click="clearFilters"
                                class="@if ($searchTerm) block @else hidden @endif text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                Tout effacer
                            </button>
                        </div>
                    </div>

                    <!-- Job Listings -->
                    <div x-show="viewMode === 'list'" class="space-y-6">
                        @foreach ($offres as $offre)
                            <div
                                class="bg-white rounded-lg shadow-sm p-6 hover:shadow-lg transition-all duration-300 group border-l-4 border-blue-500">
                                <div class="flex flex-col sm:flex-row sm:items-start">
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h3
                                                    class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                                    {{ $offre->nom_enquete }}</h3>
                                                <p class="text-blue-600 font-medium">
                                                    {{ $offre->details_enquete ?? 'Aucune description' }}</p>
                                            </div>
                                            <span x-show="isUrgent('{{ $offre->date_limite }}')"
                                                class="inline-flex items-center px-3 py-2 mx-4 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                                <i class="fas fa-exclamation-circle h-3.5 w-3.5 mr-1.5"></i> Urgent
                                            </span>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar h-4 w-4 mr-1.5 text-gray-500"></i>
                                                <span x-text="formatDate('{{ $offre->date_limite }}')"></span>
                                            </div>
                                        </div>
                                        <p class="text-gray-700 line-clamp-2 mb-4">
                                            {{ $offre->details_enquete ?? 'Aucune description disponible' }}</p>
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @foreach (array_slice($offre->questionFormulaire->pluck('question')->toArray(), 0, 3) as $req)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">{{ $req }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div
                                        class="flex sm:flex-col justify-between sm:justify-start sm:items-end gap-3 mt-4 sm:mt-0">
                                        <button wire:click="selectOffer({{ $offre->id }})" @click="openModal = true"
                                            class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-100 sm:w-full">Détails</button>
                                        <a href="{{ route('postuler', $offre->id) }}"
                                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 sm:w-full">Postuler</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div x-show="viewMode === 'card'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($offres as $offre)
                            <div
                                class="bg-white rounded-lg shadow-sm p-6 hover:shadow-lg transition-all duration-300 group">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-4">
                                            <div>
                                                <h3
                                                    class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                                    {{ $offre->nom_enquete }}</h3>
                                                <p class="text-blue-600 font-medium">
                                                    {{ $offre->details_enquete ?? 'Aucune description' }}</p>
                                            </div>
                                            <span x-show="isUrgent('{{ $offre->date_limite }}')"
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                                <i class="fas fa-exclamation-circle h-3.5 w-3.5 mr-1.5"></i> Urgent
                                            </span>
                                        </div>
                                        <p class="text-gray-700 line-clamp-3 mb-4">
                                            {{ $offre->details_enquete ?? 'Aucune description disponible' }}</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach (array_slice($offre->questionFormulaire->pluck('question')->toArray(), 0, 3) as $req)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">{{ $req }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-6 pt-5 border-t border-gray-100">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-calendar h-4 w-4 text-gray-500"></i>
                                                <span class="text-sm text-gray-600">
                                                    Date limite: <span
                                                        x-text="formatDate('{{ $offre->date_limite }}')"></span>
                                                    <span class="ml-2 font-medium"
                                                        :class="{
                                                            'text-red-600': getDaysUntilDeadline(
                                                                '{{ $offre->date_limite }}') <= 3
                                                        }">(<span
                                                            x-text="getDaysUntilDeadline('{{ $offre->date_limite }}')"></span>
                                                        jours restants)</span>
                                                </span>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button wire:click="selectOffer({{ $offre->id }})"
                                                    @click="openModal = true"
                                                    class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-100">Détails</button>
                                                <a href="{{ route('postuler', $offre->id) }}"
                                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Postuler</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($offres->isEmpty())
                        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                            <i class="fas fa-briefcase h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Aucune offre d'emploi trouvée</h3>
                            <p class="text-gray-600 mb-6">
                                @if ($searchTerm)
                                    Essayez d'ajuster vos critères de recherche pour voir plus de résultats.
                                @else
                                    Actuellement, il n'y a pas d'offres d'emploi actives. Veuillez vérifier plus tard.
                                @endif
                            </p>
                            <button wire:click="clearFilters"
                                class="@if ($searchTerm) block @else hidden @endif mx-auto border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-100">
                                Réinitialiser les filtres
                            </button>
                        </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $offres->links() }}
                    </div>
                </div>

                <!-- Right Column - About INSTAT -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="mb-6">
                            <img class="w-full h-40 mb-4" src="{{ asset('img/instat-logo.png') }}" alt="Logo INSTAT">
                            <p class="text-gray-700 mb-4">
                                L'Institut National de la Statistique (INSTAT) est l'institution centrale du système
                                statistique national à Madagascar.
                            </p>
                            <p class="text-gray-700">
                                Notre mission est de produire, analyser et diffuser des informations statistiques fiables
                                pour le développement du pays.
                            </p>
                        </div>
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-xl font-bold text-blue-900 mb-4">Pourquoi postuler à l'INSTAT ?</h3>
                            <ul class="space-y-3">
                                @foreach (['Contribuez au développement national', 'Environnement de travail professionnel', 'Équipe dynamique et passionnée', 'Impact réel sur les politiques publiques'] as $item)
                                    <li class="flex items-start">
                                        <div
                                            class="flex-shrink-0 mt-1 mr-3 w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-circle text-blue-600 text-xs"></i>
                                        </div>
                                        <span class="text-gray-700">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <br>
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-xl font-bold text-blue-900 mb-4">Des emplois qui vous correspondent</h3>
                            <p class="text-gray-700 mb-4">
                                Avec l'INSTAT, vous avez accès à des opportunités professionnelles qui correspondent à votre
                                profil et à vos aspirations.
                            </p>
                            <p class="text-gray-700">
                                Notre plateforme de recrutement vous permet de trouver rapidement des postes pertinents dans
                                votre domaine d'expertise.
                            </p>
                        </div>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 rounded-lg shadow-sm p-6 mt-6">
                        <h3 class="text-xl font-bold text-blue-900 mb-4">Conseils pour votre candidature</h3>
                        <ul class="space-y-3">
                            @foreach (['Adaptez votre CV au poste visé', 'Mettez en avant vos compétences clés', 'Personnalisez votre lettre de motivation', 'Vérifiez les dates limites de candidature', "Préparez-vous pour l'entretien"] as $item)
                                <li class="flex items-start">
                                    <div
                                        class="flex-shrink-0 mt-1 mr-3 w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-circle text-blue-600 text-xs"></i>
                                    </div>
                                    <span class="text-gray-700">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="openModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg max-w-2xl w-full">
                <template x-if="selectedOffer">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h2 x-text="selectedOffer.nom_enquete" class="text-2xl font-semibold"></h2>
                            <button @click="openModal = false" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times h-5 w-5"></i>
                            </button>
                        </div>
                        <p x-text="selectedOffer.details_enquete || 'Aucune description disponible'"
                            class="text-gray-600 mb-4"></p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                    <i class="fas fa-calendar h-5 w-5 text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Date limite</p>
                                    <p class="font-medium" x-text="formatDate(selectedOffer.date_limite)"></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                    <i class="fas fa-briefcase h-5 w-5 text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Priorité</p>
                                    <p class="font-medium" x-text="selectedOffer.priorite || 'Non spécifié'"></p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6 mt-6">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">Description de l'enquête</h4>
                                <p class="text-gray-700 leading-relaxed"
                                    x-text="selectedOffer.details_enquete || 'Aucune description disponible'"></p>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">Exigences</h4>
                                <ul class="space-y-2">
                                    <template x-for="question in selectedOffer.questionFormulaire" :key="question.id">
                                        <li class="flex items-start">
                                            <span class="flex-shrink-0 mt-1 mr-2">
                                                <div class="h-1.5 w-1.5 rounded-full bg-blue-500"></div>
                                            </span>
                                            <span class="text-gray-700" x-text="question.question"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                            <button @click="openModal = false"
                                class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-100 w-full sm:w-auto">Fermer</button>
                            {{-- <a href="{{ route('postuler', $selectedOffer->id) }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full sm:w-auto">Postuler</a>
                        </div> --}}
                        </div>
                </template>
            </div>
        </div>
    </div>
@endsection
