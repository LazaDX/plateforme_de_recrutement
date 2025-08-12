@php
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;
    Carbon::setLocale('fr');

    $conseilsDuJour = [
        'Complétez votre profil pour augmenter vos chances d\'être sélectionné.',
        'Postulez rapidement aux offres qui vous intéressent - les places sont limitées.',
        'Lisez attentivement les descriptions des offres avant de postuler.',
        'Mettez à jour régulièrement vos informations de contact.',
        'N\'hésitez pas à postuler à plusieurs offres pour maximiser vos opportunités.',
    ];
@endphp

@extends('frontOffice.layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <div id="dashboard-app" class="min-h-screen bg-gray-50">
        <!-- Header avec informations utilisateur -->
        <section class="bg-gradient-to-r from-blue-900 to-green-600 text-white py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">
                            Bonjour, <span v-text="userInfo.nom ?? 'Enquêteur'"></span> !
                        </h1>
                        <p class="text-lg opacity-90">Voici un aperçu de votre activité</p>
                        <p class="text-sm opacity-75 mt-1">
                            Dernière connexion : <span v-text="formatDateTime(userInfo.last_login_at)"></span>
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-4 min-w-fit">
                        <div class="text-center">
                            <div class="text-2xl font-bold" v-text="currentDay"></div>
                            <div class="text-sm opacity-75" v-text="currentMonth"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Statistiques principales -->
            <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total candidatures</p>
                            <p class="text-2xl font-bold text-gray-900" v-text="stats.total || 0"></p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class="fas fa-briefcase text-blue-600"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <span v-if="stats.total > 0">@{{ stats.ce_mois || 0 }} ce mois-ci</span>
                        <span v-else>Commencez à postuler !</span>
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">En attente</p>
                            <p class="text-2xl font-bold text-gray-900" v-text="stats.en_attente || 0"></p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-3">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Réponse en cours</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Acceptées</p>
                            <p class="text-2xl font-bold text-gray-900" v-text="stats.accepte || 0"></p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Taux : <span v-text="calculateSuccessRate()"></span>%
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Candidatures récentes -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Candidatures récentes</h3>
                                <a href="{{ route('enqueteur.candidatures') }}"
                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    Voir tout
                                </a>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <div v-if="recentCandidatures.length > 0">
                                <div v-for="candidature in recentCandidatures" :key="candidature.id"
                                    class="p-6 hover:bg-gray-50 transition-colors duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-grow">
                                            <h4 class="font-medium text-gray-900 mb-1"
                                                v-text="candidature.offre.nom_enquete"></h4>
                                            <div class="flex items-center gap-4 text-sm text-gray-500 mb-2">

                                                <span v-text="formatDate(candidature.date_postule)"></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span :class="getStatusClass(candidature.status_postule)"
                                                    class="px-2 py-1 rounded-full text-xs font-medium border"
                                                    v-text="getStatusText(candidature.status_postule)">
                                                </span>
                                                <span class="text-xs text-gray-500"
                                                    v-text="capitalizeFirst(candidature.type_enqueteur)"></span>
                                            </div>
                                        </div>
                                        <a :href="`/enqueteur/offre/${candidature.offre.id}`"
                                            class="ml-4 text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="p-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-3"></i>
                                <p>Aucune candidature récente</p>
                                <a href="{{ route('enqueteur.offre') }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                                    Découvrir les offres
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar avec actions rapides et infos -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Actions rapides -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
                        <div class="space-y-3">
                            <a href="{{ route('enqueteur.offre') }}"
                                class="flex items-center gap-3 p-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                <div class="bg-blue-100 rounded-full p-2">
                                    <i class="fas fa-search text-blue-600"></i>
                                </div>
                                <span>Rechercher des offres</span>
                            </a>
                            <a href="{{ route('enqueteur.candidatures') }}"
                                class="flex items-center gap-3 p-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                <div class="bg-green-100 rounded-full p-2">
                                    <i class="fas fa-list text-green-600"></i>
                                </div>
                                <span>Mes candidatures</span>
                            </a>
                        </div>
                    </div>

                    <!-- Nouvelles offres -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Nouvelles offres</h3>
                        <div v-if="nouvellesOffres.length > 0" class="space-y-3">
                            <div v-for="offre in nouvellesOffres.slice(0, 3)" :key="offre.id"
                                class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors duration-200">
                                <h4 class="font-medium text-sm text-gray-900 mb-1" v-text="truncate(offre.nom_enquete, 40)">
                                </h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500"
                                        v-text="formatDateTime(offre.created_at, true)"></span>
                                    <a :href="`/enqueteur/offre/${offre.id}`"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        Voir
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('enqueteur.offre') }}"
                                class="block text-center text-sm text-blue-600 hover:text-blue-800 font-medium mt-4">
                                Voir toutes les offres
                            </a>
                        </div>
                        <p v-else class="text-sm text-gray-500">Aucune nouvelle offre disponible</p>
                    </div>

                    <!-- Conseils -->
                    <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            Conseil du jour
                        </h3>
                        <p class="text-sm text-gray-700 mb-3" v-text="conseilDuJour"></p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>
        const {
            createApp,
            ref,
            computed,
            onMounted
        } = Vue;

        createApp({
            setup() {
                const userInfo = ref(@json(Auth::user() ?? []));
                const stats = ref(@json($stats ?? []));
                const recentCandidatures = ref(@json($recentCandidatures ?? []));
                const nouvellesOffres = ref(@json($nouvellesOffres ?? []));
                const conseilDuJour = ref('');

                const conseilsDuJour = @json($conseilsDuJour);

                const currentDay = computed(() => new Date().getDate());
                const currentMonth = computed(() => new Intl.DateTimeFormat('fr-FR', {
                    month: 'long',
                    year: 'numeric'
                }).format(new Date()));

                const getStatusClass = (status) => {
                    const classes = {
                        'en_attente': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'accepte': 'bg-green-100 text-green-800 border-green-200',
                        'refuse': 'bg-red-100 text-red-800 border-red-200',
                        'termine': 'bg-blue-100 text-blue-800 border-blue-200'
                    };
                    return classes[status] || 'bg-gray-100 text-gray-800 border-gray-200';
                };

                const getStatusText = (status) => {
                    const texts = {
                        'en_attente': 'En attente',
                        'accepte': 'Accepté',
                        'refuse': 'Refusé',
                        'termine': 'Terminé'
                    };
                    return texts[status] || status;
                };

                const formatDate = (dateString) => {
                    return new Date(dateString).toLocaleDateString('fr-FR', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                };

                const formatDateTime = (dateString, diffForHumans = false) => {
                    if (!dateString) return 'Première connexion';
                    const date = new Date(dateString);
                    if (diffForHumans) {
                        const now = new Date();
                        const diffInSeconds = Math.floor((now - date) / 1000);
                        if (diffInSeconds < 60) return 'il y a quelques secondes';
                        if (diffInSeconds < 3600) return `il y a ${Math.floor(diffInSeconds / 60)} minutes`;
                        if (diffInSeconds < 86400) return `il y a ${Math.floor(diffInSeconds / 3600)} heures`;
                        return `il y a ${Math.floor(diffInSeconds / 86400)} jours`;
                    }
                    return date.toLocaleString('fr-FR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                };

                const calculateSuccessRate = () => {
                    return stats.value.total > 0 ?
                        Math.round((stats.value.accepte || 0) / stats.value.total * 100, 1) : 0;
                };

                const capitalizeFirst = (str) => {
                    if (!str) return '';
                    return str.charAt(0).toUpperCase() + str.slice(1);
                };

                const truncate = (str, length) => {
                    if (str.length <= length) return str;
                    return str.substring(0, length) + '...';
                };

                onMounted(() => {
                    conseilDuJour.value = conseilsDuJour[Math.floor(Math.random() * conseilsDuJour.length)];
                });

                return {
                    userInfo,
                    stats,
                    recentCandidatures,
                    nouvellesOffres,
                    conseilDuJour,
                    currentDay,
                    currentMonth,
                    getStatusClass,
                    getStatusText,
                    formatDate,
                    formatDateTime,
                    calculateSuccessRate,
                    capitalizeFirst,
                    truncate
                };
            }
        }).mount('#dashboard-app');
    </script>
@endsection
