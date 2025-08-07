@extends('backOffice.layouts.admin')

@section('title', 'Liste des enquêteurs')

@section('content')
    <div id="appEnqueteurs" class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-600 mb-6">Gestion des enquêteurs</h1>

        <div
            class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 transform hover:shadow-lg mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div class="w-full md:w-auto flex flex-col md:flex-row gap-3">
                        <div class="relative">
                            <input type="text" v-model="searchQuery" @input="filterEnqueteurs"
                                placeholder="Recherche par nom..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 w-full md:w-64 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
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
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Photo
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    @click="sortBy('nom')">
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
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    @click="sortBy('prenom')">
                                    <div class="flex items-center gap-1">
                                        Prénom
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
                                    @click="sortBy('email')">
                                    <div class="flex items-center gap-1">
                                        Email
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
                                    @click="sortBy('date_de_naissance')">
                                    <div class="flex items-center gap-1">
                                        Date de naissance
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="enqueteur in filteredEnqueteurs" :key="enqueteur.id"
                                class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">@{{ enqueteur.id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" :src="getAvatarUrl(enqueteur)"
                                            :alt="enqueteur.nom + ' ' + enqueteur.prenom">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">@{{ enqueteur.nom }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">@{{ enqueteur.prenom }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">@{{ enqueteur.email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">@{{ formatDate(enqueteur.date_de_naissance) }}</div>
                                </td>
                            </tr>
                            <tr v-if="filteredEnqueteurs.length === 0">
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucun enquêteur trouvé
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
                                    class="font-medium">@{{ Math.min(currentPage * perPage, totalEnqueteurs) }}</span> sur <span
                                    class="font-medium">@{{ totalEnqueteurs }}</span> résultats
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
                                        'z-10 bg-blue-50 border-blue-500 text-blue-600': currentPage === page,
                                        'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== page
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
    </div>
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script>
        const {
            createApp,
            ref,
            computed,
            onMounted
        } = Vue;

        createApp({
            setup() {
                const enqueteurs = ref([]);
                const filteredEnqueteurs = ref([]);
                const searchQuery = ref('');
                const currentPage = ref(1);
                const perPage = ref(10);
                const totalEnqueteurs = ref(0);
                const sortField = ref('nom');
                const sortDirection = ref('asc');

                const fetchEnqueteurs = async () => {
                    try {
                        const response = await fetch('/admin/enqueteurs/view', {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.content || ''
                            },
                        });

                        if (!response.ok) {
                            console.error('Erreur API:', response.status, response.statusText);
                            const text = await response.text();
                            console.error('Contenu de la réponse:', text);
                            throw new Error(`Erreur HTTP ${response.status}`);
                        }

                        const data = await response.json();
                        console.log('Données reçues:', data);
                        enqueteurs.value = Array.isArray(data) ? data : [];
                        filteredEnqueteurs.value = enqueteurs.value;
                        totalEnqueteurs.value = enqueteurs.value.length;
                    } catch (error) {
                        console.error('Erreur lors de la récupération des enquêteurs:', error);
                        enqueteurs.value = [];
                        filteredEnqueteurs.value = [];
                        totalEnqueteurs.value = 0;
                    }
                };

                const getAvatarUrl = (enqueteur) => {
                    if (enqueteur.photo) {
                        return '/storage/' + enqueteur.photo;
                    }

                    const nom = encodeURIComponent(enqueteur.nom || '');
                    const prenom = encodeURIComponent(enqueteur.prenom || '');
                    return `https://ui-avatars.com/api/?name=${nom}+${prenom}&background=random`;
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

                const filterEnqueteurs = () => {
                    let result = Array.isArray(enqueteurs.value) ? [...enqueteurs.value] : [];

                    if (searchQuery.value) {
                        const query = searchQuery.value.toLowerCase();
                        result = result.filter(enqueteur =>
                            (enqueteur.nom?.toLowerCase().includes(query) || false) ||
                            (enqueteur.prenom?.toLowerCase().includes(query) || false) ||
                            (enqueteur.email?.toLowerCase().includes(query) || false)
                        );
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

                    filteredEnqueteurs.value = result;
                    totalEnqueteurs.value = result.length;
                    currentPage.value = 1;
                };

                const sortBy = (field) => {
                    if (sortField.value === field) {
                        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
                    } else {
                        sortField.value = field;
                        sortDirection.value = 'asc';
                    }
                    filterEnqueteurs();
                };

                const totalPages = computed(() => Math.ceil(totalEnqueteurs.value / perPage.value));

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

                const paginatedEnqueteurs = computed(() => {
                    const start = (currentPage.value - 1) * perPage.value;
                    const end = start + perPage.value;
                    return Array.isArray(filteredEnqueteurs.value) ?
                        filteredEnqueteurs.value.slice(start, end) : [];
                });

                onMounted(() => {
                    fetchEnqueteurs();
                });

                return {
                    enqueteurs,
                    filteredEnqueteurs: paginatedEnqueteurs,
                    searchQuery,
                    currentPage,
                    perPage,
                    totalEnqueteurs,
                    totalPages,
                    visiblePages,
                    sortField,
                    sortDirection,
                    formatDate,
                    filterEnqueteurs,
                    sortBy,
                    getAvatarUrl
                };
            }
        }).mount('#appEnqueteurs');
    </script>
@endsection
