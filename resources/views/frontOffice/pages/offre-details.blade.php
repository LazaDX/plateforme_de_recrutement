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
        <main id="vue-app" class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
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
                            {!! $offre->details_enquete !!}
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
                                <p class="text-sm text-gray-500">Date de création de l'offre</p>
                                <p class="font-medium">
                                    {{ optional($offre->created_at)->format('d/m/Y') ?? 'Non défini' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="far fa-clock text-gray-500 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-sm text-gray-500">Date limite du postule</p>
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
                            class="block w-full bg-gradient-to-r from-blue-400 to-green-500 hover:from-blue-600 hover:to-green-600 text-white py-4 px-4 rounded-2xl text-lg font-medium text-center transition-all duration-300 shadow-md hover:shadow-lg">
                            Postuler <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </section>
            </div>

            <!-- Modal Vue.js -->
            <div v-show="showModal"
                class="fixed inset-0 z-[1000] bg-black bg-opacity-60 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out"
                :class="{ 'opacity-0 pointer-events-none': !showModal, 'opacity-100': showModal }">
                <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl transform transition-all duration-300 ease-in-out"
                    :class="{ 'translate-y-10 opacity-0': !showModal, 'translate-y-0 opacity-100': showModal }">
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
                        <form @submit.prevent="submitForm" ref="applicationForm" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="offre_id" :value="offreId">

                            <div v-for="question in questions" :key="question.id" class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    @{{ question.label }}
                                    <span v-if="question.obligation" class="text-red-500">*</span>
                                </label>

                                <!-- Champ texte -->
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

                                <div v-else-if="question.type === 'champ_multiple'" class="space-y-3">
                                    <div class="space-y-2">
                                        <div v-for="(value, index) in responses[question.id].multiple_values"
                                            :key="index" class="flex gap-2 items-center">
                                            <div class="relative flex-1">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                    <i class="fas fa-edit"></i>
                                                </div>
                                                <input type="text"
                                                    v-model="responses[question.id].multiple_values[index]"
                                                    :placeholder="`${question.label} ${index + 1}`"
                                                    class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            </div>
                                            <button type="button" @click="removeMultipleValue(question.id, index)"
                                                v-if="responses[question.id].multiple_values.length > 1"
                                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button type="button" @click="addMultipleValue(question.id)"
                                        class="w-full py-2 px-4 border-2 border-dashed border-gray-300 text-gray-600 rounded-lg hover:border-blue-400 hover:text-blue-600 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Ajouter un autre @{{ question.label.toLowerCase() }}
                                    </button>

                                    <!-- Input caché pour stocker toutes les valeurs -->
                                    <input type="hidden" :name="'reponses[' + question.id + '][valeur]'"
                                        :value="responses[question.id].multiple_values.filter(v => v.trim()).join(', ')">

                                    <!-- Affichage du résumé si des valeurs sont présentes -->
                                    <div v-if="responses[question.id].multiple_values.filter(v => v.trim()).length > 0"
                                        class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <p class="text-sm font-medium text-blue-800 mb-2">
                                            <i class="fas fa-list mr-2"></i>Valeurs ajoutées :
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                v-for="value in responses[question.id].multiple_values.filter(v => v.trim())"
                                                :key="value"
                                                class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                @{{ value }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Zone de texte -->
                                <div v-else-if="question.type === 'long_texte'" class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 pt-2 flex items-start pointer-events-none text-gray-400">
                                        <i class="fas fa-align-left"></i>
                                    </div>
                                    <textarea v-model="responses[question.id].valeur" :name="'reponses[' + question.id + '][valeur]'"
                                        class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        rows="4" :required="question.obligation"></textarea>
                                </div>

                                <!-- Nombre -->
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

                                <!-- Email -->
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

                                <!-- Date -->
                                <div v-else-if="question.type === 'date'" class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <input type="date" v-model="responses[question.id].valeur"
                                        :name="'reponses[' + question.id + '][valeur]'"
                                        class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        :required="question.obligation">
                                </div>

                                <!-- Liste déroulante -->
                                <div v-else-if="question.type === 'liste'" class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-list"></i>
                                    </div>
                                    <select v-model="responses[question.id].valeur"
                                        :name="'reponses[' + question.id + '][valeur]'"
                                        class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none"
                                        :required="question.obligation">
                                        <option value="">Sélectionnez une option...</option>
                                        <option v-for="option in question.options_array" :key="option"
                                            :value="option">
                                            @{{ option }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Choix multiple -->
                                <div v-else-if="question.type === 'choix_multiple'" class="space-y-2">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <label v-for="option in question.options_array" :key="option"
                                            class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                            <input type="checkbox" :value="option"
                                                v-model="responses[question.id].selected_options"
                                                class="mr-3 text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">@{{ option }}</span>
                                        </label>
                                    </div>
                                    <input type="hidden" :name="'reponses[' + question.id + '][valeur]'"
                                        :value="responses[question.id].selected_options.join(', ')">
                                </div>

                                <div v-else-if="question.type === 'choix_avec_condition'" class="space-y-4">
                                    @include('frontOffice.components.offer-details-choixCondition')
                                </div>

                                <!-- Image -->
                                <div v-else-if="question.type === 'image'" class="space-y-3">
                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                        <div v-if="!responses[question.id].preview">
                                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                            <p class="text-gray-600 mb-2">Cliquez pour sélectionner une image</p>
                                            <p class="text-sm text-gray-500">JPEG, PNG, JPG (max 5MB)</p>
                                        </div>
                                        <div v-else class="space-y-3">
                                            <img :src="responses[question.id].preview" alt="Aperçu"
                                                class="max-w-48 max-h-48 mx-auto rounded-lg shadow-md">
                                            <p class="text-sm text-green-600">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Image sélectionnée
                                            </p>
                                            <button type="button" @click="removeFile(question.id)"
                                                class="text-red-600 hover:text-red-800 text-sm">
                                                <i class="fas fa-trash mr-1"></i>Supprimer
                                            </button>
                                        </div>
                                        <input type="file" :ref="'file_' + question.id"
                                            @change="handleFileUpload(question.id, $event)"
                                            accept="image/jpeg,image/png,image/jpg" class="hidden"
                                            :required="question.obligation && !responses[question.id].file">
                                    </div>
                                    <button type="button" @click="$refs['file_' + question.id][0].click()"
                                        class="w-full py-2 px-4 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                        <i class="fas fa-image mr-2"></i>Choisir une image
                                    </button>
                                </div>

                                <!-- Fichier PDF -->
                                <div v-else-if="question.type === 'fichier'" class="space-y-3">
                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-red-400 transition-colors">
                                        <div v-if="!responses[question.id].fileName">
                                            <i class="fas fa-file-pdf text-4xl text-gray-400 mb-4"></i>
                                            <p class="text-gray-600 mb-2">Cliquez pour sélectionner un fichier PDF</p>
                                            <p class="text-sm text-gray-500">PDF uniquement (max 10MB)</p>
                                        </div>
                                        <div v-else class="space-y-3">
                                            <i class="fas fa-file-pdf text-4xl text-red-600"></i>
                                            <p class="text-sm text-green-600">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                @{{ responses[question.id].fileName }}
                                            </p>
                                            <button type="button" @click="removeFile(question.id)"
                                                class="text-red-600 hover:text-red-800 text-sm">
                                                <i class="fas fa-trash mr-1"></i>Supprimer
                                            </button>
                                        </div>
                                        <input type="file" :ref="'file_' + question.id"
                                            @change="handleFileUpload(question.id, $event)" accept="application/pdf"
                                            class="hidden"
                                            :required="question.obligation && !responses[question.id].file">
                                    </div>
                                    <button type="button" @click="$refs['file_' + question.id][0].click()"
                                        class="w-full py-2 px-4 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                        <i class="fas fa-file-pdf mr-2"></i>Choisir un fichier PDF
                                    </button>
                                </div>

                                <!-- Géographique (code existant) -->
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
        </main>

        <script>
            const {
                createApp,
                reactive
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
                            selected_options: [], // Pour choix multiples
                            multiple_values: [''],
                            file: null,
                            fileName: '',
                            preview: null,
                            selected_main_option: '',
                            conditions: {},
                            condition_multiple: {},
                            condition_files: {}
                        };
                        this.districts[question.id] = [];
                        this.communes[question.id] = [];

                        if (question.type === 'choix_avec_condition' && question.conditional_options) {
                            try {
                                question.conditional_options_array = typeof question.conditional_options ===
                                    'string' ?
                                    JSON.parse(question.conditional_options) :
                                    question.conditional_options;
                                // Vérifier que conditional_options_array est un tableau
                                if (!Array.isArray(question.conditional_options_array)) {
                                    console.warn(
                                        `conditional_options_array n'est pas un tableau pour la question ${question.id}`
                                    );
                                    question.conditional_options_array = [];
                                }
                            } catch (e) {
                                console.error(
                                    `Erreur parsing conditional_options pour la question ${question.id}:`, e
                                );
                                question.conditional_options_array = [];
                            }

                            // Initialiser les choix multiples pour les conditions
                            question.conditional_options_array.forEach(option => {
                                if (option.conditions && Array.isArray(option.conditions)) {
                                    option.conditions.forEach(condition => {
                                        if (condition.type === 'choix_multiple') {
                                            this.responses[question.id].condition_multiple[
                                                option.label + '_' + condition.label] = [];
                                        }
                                    });
                                }
                            });
                        }

                        // Préparer les options pour les listes et choix multiples
                        if (question.options) {
                            question.options_array = question.options.split('|');
                        }

                        if (question.type === 'champ_multiple') {
                            this.responses[question.id].multiple_values = [''];
                        }
                    });
                },
                methods: {
                    handleMainOptionChange(questionId, optionLabel) {
                        // Réinitialiser les conditions quand on change d'option principale
                        this.responses[questionId].conditions = {};
                        this.responses[questionId].condition_multiple = {};
                        this.responses[questionId].condition_files = {};

                        // Réinitialiser les choix multiples pour la nouvelle option
                        const question = this.questions.find(q => q.id === questionId);
                        if (question && question.conditional_options_array) {
                            const selectedOption = question.conditional_options_array.find(opt => opt.label ===
                                optionLabel);
                            if (selectedOption && selectedOption.conditions) {
                                selectedOption.conditions.forEach(condition => {
                                    if (condition.type === 'choix_multiple') {
                                        this.responses[questionId].condition_multiple[optionLabel + '_' +
                                            condition.label] = [];
                                    }
                                });
                            }
                        }
                    },

                    handleConditionFileUpload(questionId, optionLabel, conditionLabel, conditionType, event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        const maxSize = conditionType === 'image' ? 5 * 1024 * 1024 : 10 * 1024 * 1024;
                        if (file.size > maxSize) {
                            this.$notyf.error(
                                `Le fichier est trop volumineux (max ${conditionType === 'image' ? '5MB' : '10MB'})`
                            );
                            event.target.value = '';
                            return;
                        }

                        const fileKey = optionLabel + '_' + conditionLabel;
                        this.responses[questionId].condition_files[fileKey] = file;
                        this.responses[questionId].condition_files[fileKey + '_name'] = file.name;

                        // Créer aperçu pour les images
                        if (conditionType === 'image') {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.responses[questionId].condition_files[fileKey + '_preview'] = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    },

                    // Méthode pour ajouter une nouvelle valeur dans un champ multiple
                    addMultipleValue(questionId) {
                        if (!this.responses[questionId].multiple_values) {
                            this.responses[questionId].multiple_values = [''];
                        }
                        this.responses[questionId].multiple_values.push('');
                    },

                    // Méthode pour supprimer une valeur d'un champ multiple
                    removeMultipleValue(questionId, index) {
                        if (this.responses[questionId].multiple_values.length > 1) {
                            this.responses[questionId].multiple_values.splice(index, 1);
                        }
                    },
                    handleFileUpload(questionId, event) {
                        const file = event.target.files[0];
                        const question = this.questions.find(q => q.id === questionId);

                        if (!file) return;

                        // Validation de la taille
                        const maxSize = question.type === 'image' ? 5 * 1024 * 1024 : 10 * 1024 *
                            1024; // 5MB pour image, 10MB pour PDF
                        if (file.size > maxSize) {
                            this.$notyf.error(
                                `Le fichier est trop volumineux (max ${question.type === 'image' ? '5MB' : '10MB'})`
                            );
                            return;
                        }

                        this.responses[questionId].file = file;
                        this.responses[questionId].fileName = file.name;

                        // Créer aperçu pour les images
                        if (question.type === 'image') {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.responses[questionId].preview = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    },

                    removeFile(questionId) {
                        this.responses[questionId].file = null;
                        this.responses[questionId].fileName = '';
                        this.responses[questionId].preview = null;

                        // Reset input file
                        const fileInput = this.$refs['file_' + questionId][0];
                        if (fileInput) {
                            fileInput.value = '';
                        }
                    },

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
                        const formData = new FormData();

                        // Ajouter les données de base
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('offre_id', this.offreId);


                        Object.keys(this.responses).forEach(questionId => {
                            const response = this.responses[questionId];
                            const question = this.questions.find(q => q.id == questionId);
                            if (question.type === 'choix_avec_condition') {
                                formData.append(`reponses[${questionId}][valeur]`, response
                                    .selected_main_option || '');


                                Object.keys(response.conditions).forEach(conditionKey => {
                                    if (response.conditions[conditionKey]) {
                                        formData.append(
                                            `reponses[${questionId}][conditions][${conditionKey}]`,
                                            response.conditions[conditionKey]);
                                    }
                                });

                                Object.keys(response.condition_files).forEach(fileKey => {
                                    if (fileKey.endsWith('_name') || fileKey.endsWith('_preview'))
                                        return;
                                    const file = response.condition_files[fileKey];
                                    if (file instanceof File) {
                                        formData.append(
                                            `reponses[${questionId}][condition_files][${fileKey}]`,
                                            file);
                                    }
                                });
                            } else if (question.type === 'choix_multiple') {
                                formData.append(`reponses[${questionId}][valeur]`, response.selected_options
                                    .join(', '));
                            } else if (question.type === 'champ_multiple') {
                                const validValues = response.multiple_values.filter(v => v && v.trim());
                                formData.append(`reponses[${questionId}][valeur]`, validValues.join(', '));
                            } else if (question.type === 'image' || question.type === 'fichier') {
                                if (response.file) {
                                    formData.append(`reponses[${questionId}][fichier]`, response.file);
                                }
                            } else {
                                formData.append(`reponses[${questionId}][valeur]`, response.valeur || '');
                            }

                            // Données géographiques
                            if (response.region_id) {
                                formData.append(`reponses[${questionId}][region_id]`, response.region_id);
                            }
                            if (response.district_id) {
                                formData.append(`reponses[${questionId}][district_id]`, response
                                    .district_id);
                            }
                            if (response.commune_id) {
                                formData.append(`reponses[${questionId}][commune_id]`, response.commune_id);
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
            }).mount('#vue-app');
        </script>
    </div>
@endsection
