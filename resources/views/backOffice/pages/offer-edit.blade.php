@extends('backOffice.layouts.admin')

@section('title', 'Modifier une offre')

@section('content')
    <div id="app" class="container mx-auto p-6">
        <div class="mb-8">
            <a href="{{ route('admin.offers') }}" class='text-slate-400 hover:text-slate-600'>Liste d'offres</a> &gt;
            <a href="{{ route('admin.offers.edit', ['offer' => $offer->id]) }}"
                class='text-blue-600 hover:text-blue-800'>Modification</a>
        </div>

        <h1 class="text-2xl font-semibold mb-8">Modifier une offre</h1>

        <form @submit.prevent="saveOffre">
            <!-- Nom de l'enquête -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'enquête</label>
                <input v-model="form.nom_enquete" type="text" name="nom_enquete" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" />
            </div>

            <!-- Détails de l'enquête -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Détails de l'enquête</label>
                <trix-editor input="details_enquete"
                    class="trix-content mt-1 w-full h-96 bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 overflow-y-auto"></trix-editor>
                <input id="details_enquete" type="hidden" name="details_enquete" v-model="form.details_enquete">
            </div>

            <!-- Date limite -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date limite</label>
                <input v-model="form.date_limite" type="date" name="date_limite" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" />
            </div>

            <!-- Statut -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut de l'offre</label>
                <select v-model="form.status_offre" name="status_offre"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                    <option value="brouillon">Brouillon</option>
                    <option value="publiee">Publiée</option>
                    <option value="fermee">Fermée</option>
                </select>
            </div>

            <!-- Questions dynamiques -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-2">Questions du formulaire de candidature</h3>

                <div v-for="(field, index) in formulaire" :key="index"
                    class="p-3 bg-gray-50 rounded flex justify-between items-center mb-3">
                    <div>
                        <span class="font-medium">@{{ field.label }}</span>
                        <span class="text-sm text-gray-500 ml-2">(@{{ field.type }})</span>
                        <span v-if="field.obligation" class="text-red-500 ml-1">*</span>
                    </div>
                    <button type="button" @click="removeField(index)" class="text-red-600 hover:text-red-800">
                        Supprimer
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select v-model="newField.type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                            <option value="texte">Texte</option>
                            <option value="email">Email</option>
                            <option value="date">Date</option>
                            <option value="long_texte">Zone de texte</option>
                            <option value="nombre">Nombre</option>
                            <option value="file">Fichier</option>
                            <option value="geographique">Géographique</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                        <input v-model="newField.label" type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300"
                            placeholder="Nom du champ" />
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center">
                            <input v-model="newField.obligation" type="checkbox" class="mr-2" />
                            <span class="text-sm text-gray-700">Obligatoire</span>
                        </label>
                    </div>
                </div>

                <!-- Options Géographiques Améliorées -->
                <div v-if="newField.type === 'geographique'" class="mt-4 p-4 bg-gray-100 rounded">
                    <!-- Aperçu pour l'utilisateur final -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="text-sm font-medium text-blue-800 mb-3">Exemple d'aperçu pour l'utilisateur :</h4>
                        <div class="bg-white p-4 rounded border">
                            <label v-if="newField.label" class="block text-sm font-medium text-gray-700 mb-2">
                                @{{ newField.label }}
                                <span v-if="newField.obligation" class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <!-- Sélection complète -->
                                <div v-if="newField.constraint_level === 'all'">
                                    <label class="block text-xs text-gray-600 mb-1">Région</label>
                                    <select v-model="selectedRegion" @change="loadDistricts(selectedRegion)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                        <option :value="null">Sélectionner une région...</option>
                                        <option v-for="r in regions" :key="r.id" :value="r.id">
                                            @{{ r.region }}</option>
                                    </select>
                                </div>
                                <div v-if="newField.constraint_level === 'all'">
                                    <label class="block text-xs text-gray-600 mb-1">District</label>
                                    <select v-model="selectedDistrict" @change="loadCommunes(selectedDistrict)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                                        :disabled="!selectedRegion">
                                        <option :value="null">Sélectionner un district...</option>
                                        <option v-for="d in districts" :key="d.id" :value="d.id">
                                            @{{ d.district }}</option>
                                    </select>
                                </div>
                                <div v-if="newField.constraint_level === 'all'">
                                    <label class="block text-xs text-gray-600 mb-1">Commune</label>
                                    <select v-model="selectedCommune"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                                        :disabled="!selectedDistrict">
                                        <option :value="null">Sélectionner une commune...</option>
                                        <option v-for="c in communes" :key="c.id" :value="c.id">
                                            @{{ c.commune }}</option>
                                    </select>
                                </div>
                                <!-- Région + District -->
                                <div v-if="newField.constraint_level === 'region_district'">
                                    <label class="block text-xs text-gray-600 mb-1">Région</label>
                                    <select v-model="selectedRegion" @change="loadDistricts(selectedRegion)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                        <option :value="null">Sélectionner une région...</option>
                                        <option v-for="r in regions" :key="r.id" :value="r.id">
                                            @{{ r.region }}</option>
                                    </select>
                                </div>
                                <div v-if="newField.constraint_level === 'region_district'">
                                    <label class="block text-xs text-gray-600 mb-1">District</label>
                                    <select v-model="selectedDistrict"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                                        :disabled="!selectedRegion">
                                        <option :value="null">Sélectionner un district...</option>
                                        <option v-for="d in districts" :key="d.id" :value="d.id">
                                            @{{ d.district }}</option>
                                    </select>
                                </div>
                                <!-- Autres options existantes -->
                                <div v-if="newField.constraint_level === 'region'">
                                    <label class="block text-xs text-gray-600 mb-1">Région</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded text-sm" disabled>
                                        <option>Sélectionner une région...</option>
                                    </select>
                                </div>
                                <div
                                    v-if="newField.show_region && newField.constraint_level !== 'all' && newField.constraint_level !== 'region_district' && newField.constraint_level !== 'region'">
                                    <label class="block text-xs text-gray-600 mb-1">Région</label>
                                    <input type="text" :value="getSelectedRegionName"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                                        readonly>
                                </div>
                                <div
                                    v-if="newField.show_district && newField.constraint_level !== 'all' && newField.constraint_level !== 'region_district'">
                                    <label class="block text-xs text-gray-600 mb-1">District</label>
                                    <input v-if="newField.constraint_level === 'commune'" type="text"
                                        :value="getSelectedDistrictName"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                                        readonly>
                                    <select v-else class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                                        disabled>
                                        <option>Sélectionner un district...</option>
                                    </select>
                                </div>
                                <div v-if="newField.show_commune && newField.constraint_level !== 'all'">
                                    <label class="block text-xs text-gray-600 mb-1">Commune</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded text-sm" disabled>
                                        <option>Sélectionner une commune...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Niveau de contrainte -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-800 mb-3">Options de sélection géographique</h4>
                        <div class="space-y-3">
                            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'all' }">
                                <input type="radio" v-model="newField.constraint_level" value="all" class="mt-1">
                                <div>
                                    <div class="font-medium text-gray-700">Sélection complète</div>
                                    <div class="text-sm text-gray-500">L'utilisateur choisit région, district et commune
                                        via combobox</div>
                                </div>
                            </label>
                            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'region_district' }">
                                <input type="radio" v-model="newField.constraint_level" value="region_district"
                                    class="mt-1">
                                <div>
                                    <div class="font-medium text-gray-700">Région + District</div>
                                    <div class="text-sm text-gray-500">L'utilisateur choisit région et district via
                                        combobox</div>
                                </div>
                            </label>
                            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'region' }">
                                <input type="radio" v-model="newField.constraint_level" value="region" class="mt-1">
                                <div>
                                    <div class="font-medium text-gray-700">Région seule</div>
                                    <div class="text-sm text-gray-500">L'utilisateur choisit parmi toutes les régions via
                                        combobox</div>
                                </div>
                            </label>
                            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'district' }">
                                <input type="radio" v-model="newField.constraint_level" value="district"
                                    class="mt-1">
                                <div>
                                    <div class="font-medium text-gray-700">District seul</div>
                                    <div class="text-sm text-gray-500">L'utilisateur choisit un district, région en lecture
                                        seule si spécifiée</div>
                                </div>
                            </label>
                            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'commune' }">
                                <input type="radio" v-model="newField.constraint_level" value="commune"
                                    class="mt-1">
                                <div>
                                    <div class="font-medium text-gray-700">Commune seule</div>
                                    <div class="text-sm text-gray-500">L'utilisateur choisit une commune, région et
                                        district en lecture seule</div>
                                </div>
                            </label>
                            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'district_commune' }">
                                <input type="radio" v-model="newField.constraint_level" value="district_commune"
                                    class="mt-1">
                                <div>
                                    <div class="font-medium text-gray-700">District + Commune</div>
                                    <div class="text-sm text-gray-500">L'utilisateur choisit district et commune, région en
                                        lecture seule si spécifiée</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Configuration spécifique -->
                    <div v-if="newField.constraint_level === 'district'" class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <h5 class="font-medium text-gray-700 mb-3">Configuration pour district seul</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Région (optionnelle)</label>
                                <select v-model="newField.region_id" @change="loadDistricts(newField.region_id)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                                    <option :value="null">Toutes les régions</option>
                                    <option v-for="r in regions" :key="r.id" :value="r.id">
                                        @{{ r.region }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">District spécifique
                                    (optionnel)</label>
                                <select v-model="newField.district_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300"
                                    :disabled="!newField.region_id">
                                    <option :value="null">Tous les districts</option>
                                    <option v-for="d in districts" :key="d.id" :value="d.id">
                                        @{{ d.district }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div v-if="newField.constraint_level === 'commune'" class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <h5 class="font-medium text-gray-700 mb-3">Configuration pour commune seule</h5>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Région</label>
                                <select v-model="newField.region_id" @change="loadDistricts(newField.region_id)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                                    <option :value="null">Sélectionner une région...</option>
                                    <option v-for="r in regions" :key="r.id" :value="r.id">
                                        @{{ r.region }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                                <select v-model="newField.district_id" @change="loadCommunes(newField.district_id)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300"
                                    :disabled="!newField.region_id">
                                    <option :value="null">Sélectionner un district...</option>
                                    <option v-for="d in districts" :key="d.id" :value="d.id">
                                        @{{ d.district }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Commune spécifique
                                    (optionnel)</label>
                                <select v-model="newField.commune_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300"
                                    :disabled="!newField.district_id">
                                    <option :value="null">Toutes les communes</option>
                                    <option v-for="c in communes" :key="c.id" :value="c.id">
                                        @{{ c.commune }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div v-if="newField.constraint_level === 'district_commune'" class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <h5 class="font-medium text-gray-700 mb-3">Configuration pour district + commune</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Région (optionnelle)</label>
                                <select v-model="newField.region_id" @change="loadDistricts(newField.region_id)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                                    <option :value="null">Toutes les régions</option>
                                    <option v-for="r in regions" :key="r.id" :value="r.id">
                                        @{{ r.region }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé de la configuration -->
                    <div v-if="newField.constraint_level" class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <h5 class="font-medium text-green-700 mb-2">📋 Résumé de la configuration</h5>
                        <div class="text-sm text-green-600">
                            <div v-if="newField.constraint_level === 'all'">
                                L'utilisateur choisira librement région, district et commune via combobox.
                            </div>
                            <div v-else-if="newField.constraint_level === 'region_district'">
                                L'utilisateur choisira région et district via combobox.
                            </div>
                            <div v-else-if="newField.constraint_level === 'region'">
                                L'utilisateur choisira parmi toutes les régions via combobox.
                            </div>
                            <div v-else-if="newField.constraint_level === 'district' && !newField.region_id">
                                L'utilisateur choisira parmi tous les districts, sans région spécifiée.
                            </div>
                            <div
                                v-else-if="newField.constraint_level === 'district' && newField.region_id && !newField.district_id">
                                L'utilisateur choisira parmi les districts de la région
                                "<strong>@{{ getSelectedRegionName }}</strong>".
                            </div>
                            <div v-else-if="newField.constraint_level === 'district' && newField.district_id">
                                L'utilisateur verra la région "<strong>@{{ getSelectedRegionName }}</strong>" en lecture seule et
                                choisira le district "<strong>@{{ getSelectedDistrictName }}</strong>".
                            </div>
                            <div v-else-if="newField.constraint_level === 'commune' && newField.commune_id">
                                L'utilisateur verra la région "<strong>@{{ getSelectedRegionName }}</strong>" et le district
                                "<strong>@{{ getSelectedDistrictName }}</strong>" en lecture seule, et choisira la commune
                                "<strong>@{{ getSelectedCommuneName }}</strong>".
                            </div>
                            <div v-else-if="newField.constraint_level === 'commune' && !newField.commune_id">
                                L'utilisateur choisira parmi les communes du district
                                "<strong>@{{ getSelectedDistrictName }}</strong>" de la région
                                "<strong>@{{ getSelectedRegionName }}</strong>".
                            </div>
                            <div v-else-if="newField.constraint_level === 'district_commune' && !newField.region_id">
                                L'utilisateur choisira district et commune via combobox, sans région spécifiée.
                            </div>
                            <div v-else-if="newField.constraint_level === 'district_commune' && newField.region_id">
                                L'utilisateur verra la région "<strong>@{{ getSelectedRegionName }}</strong>" en lecture seule et
                                choisira district et commune via combobox.
                            </div>
                            <div v-else class="text-orange-600">
                                ⚠️ Configuration incomplète - veuillez sélectionner les options nécessaires.
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" @click="addField"
                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Ajouter ce champ
                </button>
            </div>

            <!-- Bouton de soumission -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Mettre à jour l'offre
                </button>
            </div>
        </form>
    </div>

    <!-- Inclusion des bibliothèques nécessaires -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <link rel="stylesheet" href="https://unpkg.com/notyf@3/notyf.min.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <script src="https://unpkg.com/notyf@3/notyf.min.js"></script>

    <script>
        // Configuration globale d'Axios pour le jeton CSRF
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    offerId: {{ $offer->id }},
                    form: {
                        nom_enquete: '',
                        details_enquete: '',
                        date_debut: '',
                        date_limite: '',
                        priorite: 'moyenne',
                        status_offre: 'brouillon',
                    },
                    formulaire: [],
                    newField: {
                        type: 'texte',
                        label: '',
                        obligation: false,
                        constraint_level: null,
                        region_id: null,
                        district_id: null,
                        commune_id: null,
                        show_region: false,
                        show_district: false,
                        show_commune: false,
                    },
                    regions: [],
                    districts: [],
                    communes: [],
                    selectedRegion: null,
                    selectedDistrict: null,
                    selectedCommune: null,
                    notyf: null,
                };
            },
            mounted() {
                if (typeof Notyf === 'undefined') {
                    console.error('Notyf n\'est pas chargé');
                    return;
                }
                if (typeof Trix === 'undefined') {
                    console.error('Trix n\'est pas chargé');
                    this.notyf.error('L\'éditeur de texte n\'est pas disponible.');
                    return;
                }
                this.notyf = new Notyf({
                    duration: 4000,
                    position: {
                        x: 'right',
                        y: 'top'
                    }
                });
                this.loadRegions();
                this.loadOfferData();
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.initializeTrixEditor();
                        this.loadOfferData();
                    }, 100);
                });
            },
            computed: {
                getSelectedRegionName() {
                    const region = this.regions.find(r => r.id === this.newField.region_id);
                    return region ? region.region : null;
                },
                getSelectedDistrictName() {
                    const district = this.districts.find(d => d.id === this.newField.district_id);
                    return district ? district.district : null;
                },
                getSelectedCommuneName() {
                    const commune = this.communes.find(c => c.id === this.newField.commune_id);
                    return commune ? commune.commune : null;
                },
                isConfigurationValid() {
                    if (this.newField.constraint_level === 'all') return true;
                    if (this.newField.constraint_level === 'region_district') return true;
                    if (this.newField.constraint_level === 'region') return true;
                    if (this.newField.constraint_level === 'district') return true;
                    if (this.newField.constraint_level === 'commune') return this.newField.region_id && this
                        .newField.district_id;
                    if (this.newField.constraint_level === 'district_commune') return true;
                    return false;
                }
            },
            watch: {
                'newField.constraint_level'(newVal) {
                    this.newField.region_id = null;
                    this.newField.district_id = null;
                    this.newField.commune_id = null;
                    this.districts = [];
                    this.communes = [];
                    this.newField.show_region = newVal === 'all' || newVal === 'district' || newVal === 'commune' ||
                        newVal === 'district_commune' || newVal === 'region_district';
                    this.newField.show_district = newVal === 'all' || newVal === 'district' || newVal ===
                        'commune' || newVal === 'district_commune' || newVal === 'region_district';
                    this.newField.show_commune = newVal === 'all' || newVal === 'commune' || newVal ===
                        'district_commune';
                },
                'newField.type'() {
                    if (this.newField.type === 'geographique' && !this.newField.constraint_level) {
                        this.newField.constraint_level = 'all';
                    } else if (this.newField.type !== 'geographique') {
                        this.newField.constraint_level = null;
                    }
                }
            },
            methods: {
                async loadRegions() {
                    try {
                        const response = await axios.get('{{ route('admin.regions') }}');
                        if (!Array.isArray(response.data)) {
                            throw new Error('Les données des régions ne sont pas au format attendu.');
                        }
                        this.regions = response.data;
                    } catch (error) {
                        console.error('Erreur chargement régions', error);
                        this.notyf.error(
                            'Impossible de charger les régions. Vérifiez votre connexion ou contactez l’administrateur.'
                        );
                    }
                },
                async loadDistricts(regionId) {
                    this.districts = [];
                    this.communes = [];
                    this.newField.district_id = null;
                    this.newField.commune_id = null;
                    this.selectedDistrict = null;
                    this.selectedCommune = null;
                    if (!regionId) return;
                    try {
                        const response = await axios.get(`{{ url('admin/regions') }}/${regionId}/districts`);
                        if (!Array.isArray(response.data)) {
                            throw new Error('Les données des districts ne sont pas au format attendu.');
                        }
                        this.districts = response.data;
                    } catch (error) {
                        console.error('Erreur chargement districts', error);
                        this.notyf.error('Impossible de charger les districts');
                    }
                },
                async loadCommunes(districtId) {
                    this.communes = [];
                    this.newField.commune_id = null;
                    this.selectedCommune = null;
                    if (!districtId) return;
                    try {
                        const response = await axios.get(`{{ url('admin/districts') }}/${districtId}/communes`);
                        if (!Array.isArray(response.data)) {
                            throw new Error('Les données des communes ne sont pas au format attendu.');
                        }
                        this.communes = response.data;
                    } catch (error) {
                        console.error('Erreur chargement communes', error);
                        this.notyf.error('Impossible de charger les communes');
                    }
                },
                async loadOfferData() {
                    try {
                        const response = await axios.get(`{{ url('admin/offers') }}/${this.offerId}`);
                        console.log('Réponse de getOffer:', response.data);
                        const offer = response.data;
                        this.form = {
                            nom_enquete: offer.nom_enquete || '',
                            details_enquete: offer.details_enquete || '',
                            date_debut: offer.date_debut || '',
                            date_limite: offer.date_limite || '',
                            priorite: offer.priorite || 'moyenne',
                            status_offre: offer.status_offre || 'brouillon',
                        };
                        this.formulaire = offer.formulaire || [];

                        this.$nextTick(() => {
                            const trixEditor = document.querySelector('trix-editor');
                            const hiddenInput = document.querySelector('#details_enquete');

                            if (trixEditor && hiddenInput) {
                                // Mettre à jour le contenu de l'input caché
                                hiddenInput.value = this.form.details_enquete;

                                // Déclencher l'événement pour que Trix se mette à jour
                                hiddenInput.dispatchEvent(new Event('input', {
                                    bubbles: true
                                }));

                                // Alternative: définir directement le contenu de l'éditeur
                                if (trixEditor.editor) {
                                    trixEditor.editor.loadHTML(this.form.details_enquete);
                                }
                            }
                        });


                        for (const field of this.formulaire) {
                            if (field.type === 'geographique' && field.region_id) {
                                await this.loadDistricts(field.region_id);
                                if (field.district_id) {
                                    await this.loadCommunes(field.district_id);
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Erreur chargement offre', error.response?.data || error.message);
                        this.notyf.error('Impossible de charger les données de l\'offre');
                    }
                },
                initializeTrixEditor() {
                    const trixEditor = document.querySelector('trix-editor');
                    const hiddenInput = document.querySelector('#details_enquete');

                    if (trixEditor && hiddenInput) {
                        // Écouter les changements dans Trix
                        trixEditor.addEventListener('trix-change', () => {
                            this.form.details_enquete = hiddenInput.value;
                        });

                        // Écouter les changements dans Vue pour mettre à jour Trix
                        this.$watch('form.details_enquete', (newValue) => {
                            if (hiddenInput.value !== newValue && trixEditor.editor) {
                                trixEditor.editor.loadHTML(newValue || '');
                            }
                        });
                    }
                },
                addField() {
                    if (!this.newField.label.trim()) {
                        this.notyf.error('Le label est requis');
                        return;
                    }
                    if (this.newField.type === 'geographique') {
                        if (!this.isConfigurationValid) {
                            this.notyf.error('Veuillez compléter la configuration géographique');
                            return;
                        }
                        if (this.newField.constraint_level === 'commune' && (!this.newField.region_id || !this
                                .newField.district_id)) {
                            this.notyf.error(
                                'Veuillez sélectionner une région et un district pour le niveau commune');
                            return;
                        }
                    }
                    this.formulaire.push({
                        ...this.newField
                    });
                    this.newField = {
                        type: 'texte',
                        label: '',
                        obligation: false,
                        constraint_level: null,
                        region_id: null,
                        district_id: null,
                        commune_id: null,
                        show_region: false,
                        show_district: false,
                        show_commune: false,
                    };
                    this.selectedRegion = null;
                    this.selectedDistrict = null;
                    this.selectedCommune = null;
                    this.districts = [];
                    this.communes = [];
                },
                removeField(index) {
                    this.formulaire.splice(index, 1);
                },
                async saveOffre() {
                    if (!this.form.nom_enquete.trim()) {
                        this.notyf.error("Le nom de l’enquête est requis.");
                        return;
                    }
                    if (!this.form.details_enquete.trim()) {
                        this.notyf.error("Les détails de l’enquête sont requis.");
                        return;
                    }
                    if (!this.form.date_limite) {
                        this.notyf.error("La date limite est requise.");
                        return;
                    }
                    if (this.formulaire.length === 0) {
                        this.notyf.error("Ajoutez au moins une question.");
                        return;
                    }
                    try {
                        const response = await axios.put(
                            `{{ url('admin/offers') }}/${this.offerId}`, {
                                form: this.form,
                                formulaire: this.formulaire
                            });
                        this.notyf.success('Offre mise à jour avec succès !');
                    } catch (error) {
                        console.error('Erreur lors de la mise à jour', error.response?.data || error.message);
                        this.notyf.error(
                            'Erreur lors de la mise à jour de l\'offre. Veuillez réessayer plus tard.');
                    }
                }
            }
        }).mount('#app');
    </script>
@endsection
