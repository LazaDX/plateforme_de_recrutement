@extends('frontOffice.layouts.app')

@section('title', 'Offres d\'enquête')

@section('content')
    <div x-data="jobOffersPage()" class="min-h-screen">
        <!-- Hero Banner with Search -->
        <div class="bg-gradient-to-r from-blue-900 to-green-600 text-white py-16 md:py-20 relative">
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2069&q=80')] opacity-20 bg-cover bg-center">
            </div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-3xl md:text-4xl font-bold mb-6">Rejoignez l'équipe de l'INSTAT Madagascar</h1>
                    <p class="text-lg mb-8 opacity-90">
                        Contribuez au développement statistique du pays et participez à des projets nationaux importants
                    </p>

                    <!-- Modern Search Bar -->
                    <div class="max-w-2xl mx-auto bg-white rounded-full shadow-2xl p-1">
                        <div class="relative flex items-center">
                            <div class="absolute left-4 text-gray-400">
                                <i class="fas fa-search h-5 w-5"></i>
                            </div>
                            <input type="text" placeholder="Rechercher un poste, un département..." x-model="searchTerm"
                                class="w-full pl-12 pr-4 py-4 text-gray-900 rounded-3xl focus:outline-none" />
                            <div class="absolute right-1.5">
                                <button class="px-8 py-4 rounded-3xl shadow-md hover:shadow-lg transition-all bg-blue-600">
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

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map-marker-alt h-5 w-5 text-gray-400"></i>
                                </div>
                                <select x-model="locationFilter"
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm appearance-none bg-white text-gray-700 font-medium transition-all duration-150 cursor-pointer">
                                    <option value="all" class="py-2 text-gray-700 hover:bg-blue-50">
                                        Tous les lieux
                                    </option>
                                    <option value="Antananarivo" class="py-2 text-gray-700 hover:bg-blue-50">
                                        Antananarivo
                                    </option>
                                    <option value="Mahajanga" class="py-2 text-gray-700 hover:bg-blue-50">
                                        Mahajanga
                                    </option>
                                    <option value="Fianarantsoa" class="py-2 text-gray-700 hover:bg-blue-50">
                                        Fianarantsoa
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down h-5 w-5 text-gray-400"></i>
                                </div>
                            </div>

                            <div class="flex items-center justify-end md:justify-start space-x-3">
                                <span class="text-sm font-medium text-gray-700">
                                    <span x-text="filteredOffers.length"></span> poste<span
                                        x-text="filteredOffers.length !== 1 ? 's' : ''"></span> trouvé<span
                                        x-text="filteredOffers.length !== 1 ? 's' : ''"></span>
                                </span>
                                <button x-show="searchTerm || locationFilter !== 'all' || typeFilter !== 'all'"
                                    @click="clearFilters()"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                    Tout effacer
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- La partie des offres d'emploi (list/card view) serait ici -->
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
                                Notre mission est de produire, analyser et diffuser des informations statistiques
                                fiables pour le développement du pays.
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
                                Avec l'INSTAT, vous avez accès à des opportunités professionnelles qui correspondent à
                                votre profil et à vos aspirations.
                            </p>
                            <p class="text-gray-700">
                                Notre plateforme de recrutement vous permet de trouver rapidement des postes pertinents
                                dans votre domaine d'expertise.
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
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('jobOffersPage', () => ({
                searchTerm: '',
                locationFilter: 'all',
                typeFilter: 'all',
                viewMode: 'list',

                // Mock data - en production, vous devriez charger ces données depuis le backend
                jobOffers: [{
                        id: 1,
                        title: "Statisticien Enquêteur",
                        department: "Département des Enquêtes",
                        location: "Antananarivo",
                        type: "full-time",
                        status: "active",
                        deadline: "2023-12-15",
                        salary: "1 200 000 Ar",
                        description: "Conduire des enquêtes statistiques sur le terrain et analyser les données collectées.",
                        requirements: ["Master en statistique", "Expérience en enquête terrain",
                            "Maîtrise des logiciels statistiques"
                        ],
                        applicationsCount: 12
                    },
                    // Ajoutez d'autres offres ici...
                ],

                get filteredOffers() {
                    return this.jobOffers.filter(offer => {
                        const matchesSearch = offer.title.toLowerCase().includes(this
                                .searchTerm.toLowerCase()) ||
                            offer.department.toLowerCase().includes(this.searchTerm
                                .toLowerCase());
                        const matchesLocation = this.locationFilter === 'all' || offer
                            .location === this.locationFilter;
                        const matchesType = this.typeFilter === 'all' || offer.type === this
                            .typeFilter;
                        return matchesSearch && matchesLocation && matchesType && offer
                            .status === 'active';
                    });
                },

                clearFilters() {
                    this.searchTerm = '';
                    this.locationFilter = 'all';
                    this.typeFilter = 'all';
                },

                formatDate(dateString) {
                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString('fr-FR', options);
                }
            }));
        });
    </script>

@endsection
