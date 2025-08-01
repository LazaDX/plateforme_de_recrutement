@extends('frontOffice.layouts.app')

@section('title', "Offre d'enquête")

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Bannière sobre -->
        <div class="bg-gradient-to-tr from-blue-900 to-green-600 text-white">
            <div class="max-w-6xl mx-auto px-4 py-8">
                <div class="mb-2 text-sm font-light flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Institut Statistique de Madagascar
                </div>
                <h1 class="text-3xl font-bold mb-4">{{ $offre->nom_enquete }}</h1>
                <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Priorité :
                        <span
                            class="ml-1 px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $offre->priorite === 'haute' ? 'bg-red-100 text-red-800' : ($offre->priorite === 'moyenne' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($offre->priorite) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <main class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Détails de l'enquête -->
                <section class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200 flex items-center">
                        <i class="fas fa-info-circle text-blue-900 mr-2"></i>
                        Détails de l'enquête
                    </h2>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        <div class="whitespace-pre-line mb-8 space-y-4">
                            {!! nl2br(e($offre->details_enquete)) !!}
                        </div>
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Résumé de l'offre -->
                <section class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        Calendrier
                    </h2>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-calendar-alt text-gray-500 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-sm text-gray-500">Date de début</p>
                                <p class="font-medium">
                                    {{ optional($offre->date_debut)->format('d/m/Y') ?? 'Non défini' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="far fa-clock text-gray-500 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-sm text-gray-500">Date limite</p>
                                <p class="font-medium">
                                    {{ optional(\Carbon\Carbon::parse($offre->date_limite))->format('d/m/Y') ?? 'Non défini' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Statut -->
                <section class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tasks text-blue-800 mr-2"></i>
                        Statut de l'enquête
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Statut actuel</p>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $offre->status_offre === 'publiee' ? 'bg-green-100 text-green-800' : ($offre->status_offre === 'brouillon' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $offre->status_offre)) }}
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Bouton pour ouvrir le modal -->
                <section class="p-6">
                    <div class="space-y-3">
                        <button @click="showModal = true"
                            class="block w-full bg-gradient-to-r from-blue-600 to-green-500 hover:from-blue-700 hover:to-green-600 text-white py-4 px-4 rounded-2xl text-lg font-medium text-center transition-all duration-300 shadow-md hover:shadow-lg">
                            Postuler <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </section>

                <!-- Modal Vue.js -->
                <div id="applicationModal" v-if="showModal"
                    class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center p-4 transition-opacity duration-300">
                    <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl transform transition-all duration-300"
                        :class="{ 'scale-95': !showModal, 'scale-100': showModal }">
                        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center bg-gray-50">
                            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-file-alt mr-2 text-blue-600"></i>
                                Formulaire de candidature
                            </h2>
                            <button @click="showModal = false" class="text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="p-6 bg-gray-50">
                            <form @submit.prevent="submitForm" ref="applicationForm">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="offre_id" :value="offreId">
                                <div v-for="question in questions" :key="question.id" class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        @{{ question.label }}
                                        <span v-if="question.obligation" class="text-red-500">*</span>
                                    </label>

                                    <div v-if="question.type === 'texte'" class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-font"></i>
                                        </div>
                                        <input type="text" v-model="responses[question.id].valeur"
                                            :name="'reponses[' + question.id + '][valeur]'"
                                            class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :required="question.obligation">
                                    </div>
                                    <div v-else-if="question.type === 'long_texte'" class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 pt-2 flex items-start pointer-events-none text-gray-400">
                                            <i class="fas fa-align-left"></i>
                                        </div>
                                        <textarea v-model="responses[question.id].valeur" :name="'reponses[' + question.id + '][valeur]'"
                                            class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            rows="4" :required="question.obligation"></textarea>
                                    </div>
                                    <div v-else-if="question.type === 'nombre'" class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-hashtag"></i>
                                        </div>
                                        <input type="number" v-model.number="responses[question.id].valeur"
                                            :name="'reponses[' + question.id + '][valeur]'"
                                            class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :required="question.obligation">
                                    </div>
                                    <div v-else-if="question.type === 'email'" class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <input type="email" v-model="responses[question.id].valeur"
                                            :name="'reponses[' + question.id + '][valeur]'"
                                            class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :required="question.obligation">
                                    </div>
                                    <div v-else-if="question.type === 'geographique'"
                                        class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Région -->
                                        <div v-if="question.all_regions && !question.region_id">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Région</label>
                                            <select v-model="responses[question.id].region_id"
                                                :name="'reponses[' + question.id + '][region_id]'"
                                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                @change="fetchDistricts(question.id)" :required="question.obligation">
                                                <option value="">Sélectionner une région...</option>
                                                <option v-for="region in regions" :value="region.id">
                                                    @{{ region.region }}
                                                </option>
                                            </select>
                                        </div>
                                        <div v-else-if="question.region_id">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Région</label>
                                            <input type="text" :value="question.region.region || 'Non défini'"
                                                class="w-full border border-gray-300 rounded-lg p-3 bg-gray-100 cursor-not-allowed"
                                                readonly>
                                            <input type="hidden" :name="'reponses[' + question.id + '][region_id]'"
                                                :value="question.region_id">
                                        </div>

                                        <!-- District -->
                                        <div v-if="question.all_districts && !question.district_id">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                                            <select v-model="responses[question.id].district_id"
                                                :name="'reponses[' + question.id + '][district_id]'"
                                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                @change="fetchCommunes(question.id)"
                                                :disabled="!responses[question.id].region_id"
                                                :required="question.obligation && question.all_districts">
                                                <option value="">Sélectionner un district...</option>
                                                <option v-for="district in districts[question.id]" :value="district.id">
                                                    @{{ district.district }}
                                                </option>
                                            </select>
                                        </div>
                                        <div v-else-if="question.district_id">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                                            <input type="text" :value="question.district.district || 'Non défini'"
                                                class="w-full border border-gray-300 rounded-lg p-3 bg-gray-100 cursor-not-allowed"
                                                readonly>
                                            <input type="hidden" :name="'reponses[' + question.id + '][district_id]'"
                                                :value="question.district_id">
                                        </div>

                                        <!-- Commune -->
                                        <div v-if="question.all_communes && !question.commune_id">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Commune</label>
                                            <select v-model="responses[question.id].commune_id"
                                                :name="'reponses[' + question.id + '][commune_id]'"
                                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                :disabled="!responses[question.id].district_id"
                                                :required="question.obligation && question.all_communes">
                                                <option value="">Sélectionner une commune...</option>
                                                <option v-for="commune in communes[question.id]" :value="commune.id">
                                                    @{{ commune.commune }}
                                                </option>
                                            </select>
                                        </div>
                                        <div v-else-if="question.commune_id">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Commune</label>
                                            <input type="text" :value="question.commune.commune || 'Non défini'"
                                                class="w-full border border-gray-300 rounded-lg p-3 bg-gray-100 cursor-not-allowed"
                                                readonly>
                                            <input type="hidden" :name="'reponses[' + question.id + '][commune_id]'"
                                                :value="question.commune_id">
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t border-gray-200 pt-4 mt-6 flex justify-end space-x-3">
                                    <button type="button" @click="showModal = false"
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-times mr-2"></i> Annuler
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                        :disabled="isSubmitting">
                                        <i v-if="isSubmitting" class="fas fa-spinner fa-spin mr-2"></i>
                                        <i v-else class="fas fa-paper-plane mr-2"></i>
                                        @{{ isSubmitting ? 'Envoi...' : 'Envoyer' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Dépendances -->
        <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
        <script src="https://unpkg.com/notyf@3/notyf.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/notyf@3/notyf.min.css">

        <script>
            const {
                createApp
            } = Vue;

            createApp({
                data() {
                    return {
                        showModal: false,
                        offreId: {{ $offre->id }},
                        questions: @json($offre->questionFormulaire),
                        regions: @json($regions),
                        districts: {},
                        communes: {},
                        responses: {},
                        isSubmitting: false,
                    };
                },
                created() {
                    // Initialiser les réponses pour chaque question
                    this.questions.forEach(question => {
                        this.responses[question.id] = {
                            valeur: '',
                            region_id: question.region_id || '',
                            district_id: question.district_id || '',
                            commune_id: question.commune_id || '',
                        };
                        this.districts[question.id] = [];
                        this.communes[question.id] = [];
                    });
                },
                methods: {
                    async fetchDistricts(questionId) {
                        const regionId = this.responses[questionId].region_id;
                        this.responses[questionId].district_id = '';
                        this.responses[questionId].commune_id = '';
                        this.districts[questionId] = [];
                        this.communes[questionId] = [];

                        if (regionId) {
                            try {
                                const response = await fetch(`{{ route('districts', ':region') }}`.replace(
                                    ':region', regionId), {
                                    headers: {
                                        'Accept': 'application/json'
                                    },
                                });
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                this.districts[questionId] = await response.json();
                            } catch (error) {
                                console.error('Erreur chargement districts:', error);
                                this.$notyf.error('Impossible de charger les districts');
                            }
                        }
                    },
                    async fetchCommunes(questionId) {
                        const districtId = this.responses[questionId].district_id;
                        this.responses[questionId].commune_id = '';
                        this.communes[questionId] = [];

                        if (districtId) {
                            try {
                                const response = await fetch(`{{ route('communes', ':district') }}`.replace(
                                    ':district', districtId), {
                                    headers: {
                                        'Accept': 'application/json'
                                    },
                                });
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                this.communes[questionId] = await response.json();
                            } catch (error) {
                                console.error('Erreur chargement communes:', error);
                                this.$notyf.error('Impossible de charger les communes');
                            }
                        }
                    },
                    async submitForm() {
                        this.isSubmitting = true;
                        const formData = new FormData(this.$refs.applicationForm);
                        formData.append('offre_id', this.offreId);

                        // Ajouter les réponses manuellement pour assurer la bonne structure
                        Object.keys(this.responses).forEach(questionId => {
                            formData.set(`reponses[${questionId}][valeur]`, this.responses[questionId]
                                .valeur || '');
                            if (this.responses[questionId].region_id) {
                                formData.set(`reponses[${questionId}][region_id]`, this.responses[
                                    questionId].region_id);
                            }
                            if (this.responses[questionId].district_id) {
                                formData.set(`reponses[${questionId}][district_id]`, this.responses[
                                    questionId].district_id);
                            }
                            if (this.responses[questionId].commune_id) {
                                formData.set(`reponses[${questionId}][commune_id]`, this.responses[
                                    questionId].commune_id);
                            }
                        });

                        try {
                            const response = await fetch('{{ route('enqueteur.offre.postuler', $offre->id) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: formData,
                            });

                            const data = await response.json();
                            if (!response.ok) {
                                console.error('Error response:', data);
                                if (data.errors) {
                                    Object.values(data.errors).forEach(err => {
                                        this.$notyf.error(err[0]);
                                    });
                                } else {
                                    this.$notyf.error(data.message ||
                                        'Une erreur s\'est produite. Vérifiez vos champs.');
                                }
                                return;
                            }

                            this.showModal = false;
                            this.$notyf.success(data.message || 'Votre candidature a été envoyée avec succès !');
                            setTimeout(() => {
                                window.location.href = '{{ route('enqueteur.offre.show', $offre->id) }}';
                            }, 1000);
                        } catch (error) {
                            console.error('Submission error:', error);
                            this.$notyf.error('Erreur inattendue. Veuillez réessayer ultérieurement.');
                        } finally {
                            this.isSubmitting = false;
                        }
                    },
                },
                mounted() {
                    // Initialiser Notyf
                    this.$notyf = new Notyf({
                        duration: 3000,
                        position: {
                            x: 'right',
                            y: 'top'
                        },
                        types: [{
                            type: 'info',
                            background: 'blue'
                        }],
                    });

                    // Charger les districts pour les questions géographiques avec region_id prédéfini
                    this.questions.forEach(question => {
                        if (question.type === 'geographique' && question.region_id && question.all_districts &&
                            !question.district_id) {
                            this.responses[question.id].region_id = question.region_id;
                            this.fetchDistricts(question.id);
                        }
                        if (question.type === 'geographique' && question.district_id && question.all_communes &&
                            !question.commune_id) {
                            this.responses[question.id].district_id = question.district_id;
                            this.fetchCommunes(question.id);
                        }
                    });
                },
            }).mount('#applicationModal');
        </script>
    </div>
@endsection
