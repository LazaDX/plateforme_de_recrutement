@php
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;
    Carbon::setLocale('fr');
@endphp

@extends('frontOffice.layouts.app')

@section('title', 'Mes candidatures')

@section('content')
    <div id="candidatures-app" class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <section class="bg-gradient-to-r from-blue-900 to-green-600 text-white py-12 md:py-16">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">Mes candidatures</h1>
                        <p class="text-lg opacity-90">Suivez l'état de vos candidatures</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white bg-opacity-20 rounded-lg p-4">
                            <div class="text-2xl font-bold">@{{ candidatures.length }}</div>
                            <div class="text-sm opacity-75">Candidature@{{ candidatures.length > 1 ? 's' : '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filtres et statistiques -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">Filtrer par statut :</span>
                            <select v-model="filterStatus" @change="filterCandidatures"
                                class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all">Tous</option>
                                <option value="en_attente">En attente</option>
                                <option value="accepte">Accepté</option>
                                <option value="rejete">Refusé</option>
                                <option value="termine">Terminé</option>
                            </select>
                        </div>
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="flex gap-4 text-sm">
                        <div class="text-center">
                            <div class="font-semibold text-yellow-600">@{{ stats.en_attente || 0 }}</div>
                            <div class="text-gray-500">En attente</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-green-600">@{{ stats.accepte || 0 }}</div>
                            <div class="text-gray-500">Accepté</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-red-600">@{{ stats.rejete || 0 }}</div>
                            <div class="text-gray-500">Refusé</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des candidatures -->
            <div v-if="filteredCandidatures.length > 0" class="space-y-4">
                <div v-for="candidature in paginatedCandidatures" :key="candidature.id"
                    class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                            <div class="flex-grow">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        @{{ candidature.offre.nom_enquete }}
                                    </h3>
                                    <span :class="getStatusClass(candidature.status_postule)"
                                        class="px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap">
                                        @{{ getStatusText(candidature.status_postule) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="fas fa-user-tag text-green-500"></i>
                                        <span>@{{ capitalizeFirst(candidature.type_enqueteur) }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="fas fa-calendar-alt text-orange-500"></i>
                                        <span>Postulé le @{{ formatDate(candidature.date_postule) }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="fas fa-clock text-purple-500"></i>
                                        <span>@{{ getDaysAgo(candidature.date_postule) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2 min-w-fit">
                                <a :href="`/enqueteur/offre/${candidature.offre.id}`"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-eye mr-2"></i>
                                    Voir l'offre
                                </a>

                                {{-- <button v-if="candidature.status_postule === 'en_attente'"
                                    @click="confirmCancelCandidature(candidature.id)"
                                    class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors duration-200">
                                    <i class="fas fa-times mr-2"></i>
                                    Annuler
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- État vide -->
            <div v-else class="bg-white p-12 text-center rounded-lg shadow">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-briefcase text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Aucune candidature trouvée</h3>
                <p class="text-gray-600 mb-6">
                    <span v-if="filterStatus !== 'all'">
                        Aucune candidature avec le statut "@{{ getStatusText(filterStatus) }}" n'a été trouvée.
                    </span>
                    <span v-else>
                        Vous n'avez pas encore postulé à des offres d'enquête.
                    </span>
                </p>
                <a href="{{ route('enqueteur.offre') }}"
                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-search mr-2"></i>
                    Découvrir les offres
                </a>
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="mt-8 flex justify-center">
                <nav class="flex items-center gap-2">
                    <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md">
                        Précédent
                    </button>

                    <span v-for="page in visiblePages" :key="page">
                        <button v-if="page !== '...'" @click="changePage(page)"
                            :class="page === currentPage ? 'bg-blue-600 text-white' : 'text-gray-500 bg-white hover:bg-gray-100'"
                            class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-md">
                            @{{ page }}
                        </button>
                        <span v-else class="px-3 py-2 text-sm font-medium text-gray-500">...</span>
                    </span>

                    <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md">
                        Suivant
                    </button>
                </nav>
            </div>
        </section>

        {{-- <!-- Modal de confirmation -->
        <div v-if="showCancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-2">Annuler la candidature</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Êtes-vous sûr de vouloir annuler cette candidature ? Cette action est irréversible.
                        </p>
                    </div>
                    <div class="flex gap-4 px-4 py-3">
                        <button @click="cancelCandidature"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Oui, annuler
                        </button>
                        <button @click="showCancelModal = false"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Non, garder
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const {
            createApp,
            ref,
            computed,
            onMounted
        } = Vue;

        createApp({
            setup() {
                const candidatures = ref(@json($candidatures->items() ?? []));
                const stats = ref(@json($stats ?? []));
                const filterStatus = ref('all');
                const currentPage = ref(1);
                const itemsPerPage = ref(10);
                const showCancelModal = ref(false);
                const candidatureToCancel = ref(null);

                const getStatusClass = (status) => {
                    const classes = {
                        'en_attente': 'bg-yellow-100 text-yellow-800',
                        'accepte': 'bg-green-100 text-green-800',
                        'rejete': 'bg-red-100 text-red-800',
                        'termine': 'bg-gray-100 text-gray-800'
                    };
                    return classes[status] || 'bg-gray-100 text-gray-800';
                };

                const getStatusText = (status) => {
                    const texts = {
                        'en_attente': 'En attente',
                        'accepte': 'Accepté',
                        'rejete': 'Refusé',
                        'termine': 'Terminé'
                    };
                    return texts[status] || status;
                };

                const formatDate = (dateString) => {
                    if (!dateString) return 'Date inconnue';
                    const date = new Date(dateString);
                    if (isNaN(date)) return 'Date invalide';
                    return date.toLocaleDateString('fr-FR', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                };

                const getDaysAgo = (dateString) => {
                    if (!dateString) return '';
                    const postuleDate = new Date(dateString);
                    if (isNaN(postuleDate)) return '';
                    const today = new Date();
                    const diffTime = today.getTime() - postuleDate.getTime();
                    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays === 0) return 'Aujourd\'hui';
                    if (diffDays === 1) return 'Il y a 1 jour';
                    return `Il y a ${diffDays} jours`;
                };

                const capitalizeFirst = (str) => {
                    return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
                };

                const filteredCandidatures = computed(() => {
                    if (filterStatus.value === 'all') {
                        return candidatures.value;
                    }
                    return candidatures.value.filter(candidature =>
                        candidature.status_postule === filterStatus.value
                    );
                });

                const totalPages = computed(() => {
                    return Math.ceil(filteredCandidatures.value.length / itemsPerPage.value);
                });

                const paginatedCandidatures = computed(() => {
                    const start = (currentPage.value - 1) * itemsPerPage.value;
                    const end = start + itemsPerPage.value;
                    return filteredCandidatures.value.slice(start, end);
                });

                const visiblePages = computed(() => {
                    const total = totalPages.value;
                    const current = currentPage.value;
                    const delta = 2;
                    const range = [];
                    const rangeWithDots = [];

                    for (let i = Math.max(2, current - delta); i <= Math.min(total - 1, current +
                            delta); i++) {
                        range.push(i);
                    }

                    if (current - delta > 2) {
                        rangeWithDots.push(1, '...');
                    } else {
                        rangeWithDots.push(1);
                    }

                    rangeWithDots.push(...range);

                    if (current + delta < total - 1) {
                        rangeWithDots.push('...', total);
                    } else if (total > 1) {
                        rangeWithDots.push(total);
                    }

                    return rangeWithDots;
                });

                const filterCandidatures = () => {
                    currentPage.value = 1;
                };

                const changePage = (page) => {
                    if (page >= 1 && page <= totalPages.value) {
                        currentPage.value = page;
                    }
                };

                const confirmCancelCandidature = (candidatureId) => {
                    candidatureToCancel.value = candidatureId;
                    showCancelModal.value = true;
                };

                const cancelCandidature = async () => {
                    try {
                        await axios.delete(`/enqueteur/candidature/${candidatureToCancel.value}/cancel`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        });

                        if (response.status === 200) {
                            // Supprimer la candidature de la liste
                            candidatures.value = candidatures.value.filter(c => c.id !== candidatureToCancel
                                .value);

                            // Recalculer les stats
                            updateStats();

                            showCancelModal.value = false;
                            candidatureToCancel.value = null;

                            alert(response.data.message || 'Candidature annulée avec succès');
                        } else {
                            alert('Erreur inattendue lors de l\'annulation');
                        }
                    } catch (error) {
                        console.error('Erreur lors de l\'annulation:', error);
                        alert('Erreur lors de l\'annulation de la candidature');
                    }
                };

                const updateStats = () => {
                    const newStats = {
                        en_attente: 0,
                        accepte: 0,
                        rejete: 0,
                        termine: 0
                    };

                    candidatures.value.forEach(candidature => {
                        newStats[candidature.status_postule] = (newStats[candidature.status_postule] ||
                            0) + 1;
                    });

                    stats.value = newStats;
                };

                return {
                    candidatures,
                    stats,
                    filterStatus,
                    currentPage,
                    showCancelModal,
                    candidatureToCancel,
                    filteredCandidatures,
                    paginatedCandidatures,
                    totalPages,
                    visiblePages,
                    getStatusClass,
                    getStatusText,
                    formatDate,
                    getDaysAgo,
                    capitalizeFirst,
                    filterCandidatures,
                    changePage,
                    confirmCancelCandidature,
                    cancelCandidature
                };
            }
        }).mount('#candidatures-app');
    </script>
@endsection
