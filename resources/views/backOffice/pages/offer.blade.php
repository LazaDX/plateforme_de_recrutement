@extends('backOffice.layouts.admin')

@section('title', 'Offres d\'enquêtes')

@section('content')
    <div id="app" class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Gestion des offres d'enquêtes</h1>

        <div
            class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 transform hover:shadow-lg mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <a href="{{ route('admin.offers.create') }}"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-5 py-2.5 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all flex items-center gap-2 shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Nouvelle offre
                    </a>

                    <div class="w-full md:w-auto flex flex-col md:flex-row gap-3">
                        <div class="relative">
                            <input type="text" v-model="searchQuery" @input="filterOffers"
                                placeholder="Recherche par nom..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 w-full md:w-64 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <select v-model="statusFilter" @change="filterOffers"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Tous les statuts</option>
                            <option value="publiee">Publiée</option>
                            <option value="brouillon">Brouillon</option>
                            <option value="fermee">Fermée</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    @click="sortBy('id')">
                                    <div class="flex items-center gap-1">
                                        ID
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    @click="sortBy('nom_enquete')">
                                    <div class="flex items-center gap-1">
                                        Nom
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                {{-- <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Détails</th> --}}
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    @click="sortBy('date_debut')">
                                    <div class="flex items-center gap-1">
                                        Date de création
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    @click="sortBy('date_limite')">
                                    <div class="flex items-center gap-1">
                                        Date limite de réponse
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Priorité</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="offer in filteredOffers" :key="offer.id"
                                class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">@{{ offer.id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">@{{ offer.nom_enquete }}</div>
                                </td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 max-w-xs truncate">@{{ offer.details_enquete }}</div>
                                </td> --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">@{{ formatDate(offer.created_at) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">@{{ formatDate(offer.date_limite) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="statusClasses(offer.status_offre)"
                                        class="px-2 py-1 text-xs font-semibold rounded-full">
                                        @{{ offer.status_offre }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-500">@{{ offer.priorite }}</span>
                                        <div v-if="offer.priorite === 'haute'"
                                            class="ml-2 w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                        <div v-else-if="offer.priorite === 'moyenne'"
                                            class="ml-2 w-3 h-3 bg-yellow-500 rounded-full"></div>
                                        <div v-else class="ml-2 w-3 h-3 bg-green-500 rounded-full"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a :href="'/admin/offers/' + offer.id"
                                            class="text-blue-600 hover:text-blue-900 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <a :href="'/admin/offers/' + offer.id + '/edit'"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <button @click="confirmDelete(offer)"
                                            class="text-red-600 hover:text-red-900 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredOffers.length === 0">
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucune offre trouvée
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between px-6 py-3 bg-gray-50 border-t border-gray-200">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                            :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Précédent
                        </button>
                        <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                            :disabled="currentPage === totalPages"
                            :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Suivant
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de <span class="font-medium">@{{ (currentPage - 1) * perPage + 1 }}</span> à <span
                                    class="font-medium">@{{ Math.min(currentPage * perPage, totalOffers) }}</span> sur <span
                                    class="font-medium">@{{ totalOffers }}</span> résultats
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination">
                                <button @click="currentPage = 1" :disabled="currentPage === 1"
                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Première</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button v-for="page in visiblePages" :key="page" @click="currentPage = page"
                                    :class="{
                                        'z-10 bg-blue-50 border-blue-500 text-blue-600': currentPage ===
                                            page,
                                        'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !==
                                            page
                                    }"
                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    @{{ page }}
                                </button>
                                <button @click="currentPage = totalPages" :disabled="currentPage === totalPages"
                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Dernière</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed z-100 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-30"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Supprimer l'offre</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cette offre? Cette
                                        action est irréversible.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="deleteOffer" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Supprimer
                        </button>
                        <button @click="showDeleteModal = false" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const {
            createApp,
            ref,
            computed,
            onMounted
        } = Vue;
        const notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'top'
            }
        });
        createApp({
            setup() {

                const offers = ref([]);
                const filteredOffers = ref([]);
                const searchQuery = ref('');
                const statusFilter = ref('');
                const currentPage = ref(1);
                const perPage = ref(10);
                const totalOffers = ref(0);
                const sortField = ref('nom_enquete');
                const sortDirection = ref('asc');
                const showDeleteModal = ref(false);
                const offerToDelete = ref(null);

                const fetchOffers = async () => {
                    try {
                        const response = await fetch('{{ route('admin.getAllOffers') }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            },
                        });
                        if (!response.ok) {
                            throw new Error(`Erreur HTTP ${response.status}`);
                        }
                        const data = await response.json();

                        offers.value = Array.isArray(data) ? data : [];
                        filteredOffers.value = offers.value;
                        totalOffers.value = offers.value.length;
                    } catch (error) {
                        console.error('Erreur lors de la récupération des offres:', error);
                        offers.value = [];
                        filteredOffers.value = [];
                        totalOffers.value = 0;
                    }
                };

                const formatDate = (dateString) => {
                    if (!dateString) return 'N/A';
                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString('fr-FR', options);
                };

                const statusClasses = (status) => {
                    switch (status) {
                        case 'publiee':
                            return 'bg-green-100 text-green-800';
                        case 'brouillon':
                            return 'bg-gray-100 text-gray-800';
                        case 'fermee':
                            return 'bg-red-100 text-red-800';
                        default:
                            return 'bg-gray-100 text-gray-800';
                    }
                };

                const filterOffers = () => {
                    let result = Array.isArray(offers.value) ? [...offers.value] : [];

                    if (searchQuery.value) {
                        const query = searchQuery.value.toLowerCase();
                        result = result.filter(offer =>
                            (offer.nom_enquete?.toLowerCase().includes(query) || false) ||
                            (offer.details_enquete?.toLowerCase().includes(query) || false)
                        );
                    }

                    if (statusFilter.value) {
                        result = result.filter(offer => offer.status_offre === statusFilter.value);
                    }

                    if (Array.isArray(result)) {
                        result.sort((a, b) => {
                            const fieldA = a[sortField.value] ?? '';
                            const fieldB = b[sortField.value] ?? '';
                            if (fieldA < fieldB) return sortDirection.value === 'asc' ? -1 : 1;
                            if (fieldA > fieldB) return sortDirection.value === 'asc' ? 1 : -1;
                            return 0;
                        });
                    }

                    filteredOffers.value = result;
                    totalOffers.value = result.length;
                    currentPage.value = 1;
                };

                const sortBy = (field) => {
                    if (sortField.value === field) {
                        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
                    } else {
                        sortField.value = field;
                        sortDirection.value = 'asc';
                    }
                    filterOffers();
                };

                const totalPages = computed(() => Math.ceil(totalOffers.value / perPage.value));

                const visiblePages = computed(() => {
                    const pages = [];
                    const maxVisible = 5;
                    let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
                    let end = Math.min(totalPages.value, start + maxVisible - 1);

                    if (end - start + 1 < maxVisible) {
                        start = Math.max(1, end - maxVisible + 1);
                    }

                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }

                    return pages;
                });

                const paginatedOffers = computed(() => {
                    const start = (currentPage.value - 1) * perPage.value;
                    const end = start + perPage.value;
                    return Array.isArray(filteredOffers.value) ?
                        filteredOffers.value.slice(start, end) : [];
                });

                const confirmDelete = (offer) => {
                    offerToDelete.value = offer;
                    showDeleteModal.value = true;
                };

                const deleteOffer = async () => {
                    try {
                        await fetch(`/admin/offers/${offerToDelete.value.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            }
                        });
                        await fetchOffers();
                        notyf.success("Offre supprimée avec succès.");
                        showDeleteModal.value = false;
                    } catch (error) {
                        console.error('Erreur lors de la suppression de l\'offre:', error);
                        this.notyf.error("Erreur lors de la suppression de l'offre.");
                    }
                };

                onMounted(() => {
                    fetchOffers();
                });

                return {
                    offers,
                    filteredOffers: paginatedOffers,
                    searchQuery,
                    statusFilter,
                    currentPage,
                    perPage,
                    totalOffers,
                    totalPages,
                    visiblePages,
                    sortField,
                    sortDirection,
                    showDeleteModal,
                    offerToDelete,
                    formatDate,
                    statusClasses,
                    filterOffers,
                    sortBy,
                    confirmDelete,
                    deleteOffer
                };
            }
        }).mount('#app');
    </script>
@endsection
