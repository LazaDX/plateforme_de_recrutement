@extends('backOffice.layouts.admin')

@section('title', 'Créer une nouvelle offre')

@section('content')
    <div id="app" class="container mx-auto p-6">
        <div class="mb-8">
            <a href="{{ route('admin.offers') }}" class="text-slate-400 hover:text-slate-600">Liste d'offre </a>&gt;<a
                href="{{ route('admin.offers.create') }}" class="text-blue-600 hover:text-blue-800">Création</a>
        </div>

        <h1 class="text-2xl font-semibold mb-8">Créer une nouvelle offre</h1>

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

            <!-- Priorité -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                <select v-model="form.priorite" name="priorite"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                    <option value="urgent">Urgent</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="basse">Basse</option>
                </select>
            </div>

            <!-- Questions dynamiques -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-2">Questions du formulaire de candidature</h3>

                <div v-for="(field, index) in formulaire" :key="index"
                    class="p-4 bg-gray-50 rounded-lg flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-medium">@{{ field.label }}</span>
                            <span class="text-sm text-gray-500">(@{{ field.type }})</span>
                            <span v-if="field.obligation" class="text-red-500">*</span>
                        </div>
                        <div v-if="field.options && (field.type === 'choix_multiple' || field.type === 'liste')"
                            class="text-xs text-gray-600 mt-1">
                            Options: @{{ field.options.join(', ') }}
                        </div>
                        <div v-if="field.type === 'geographique'" class="text-xs text-gray-600 mt-1">
                            Configuration: @{{ getGeoConfigSummary(field) }}
                        </div>
                    </div>
                    <button type="button" @click="removeField(index)"
                        class="text-red-600 hover:text-red-800 ml-3 p-1 rounded hover:bg-red-50">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h4 class="font-medium text-gray-800 mb-4">Ajouter un nouveau champ</h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select v-model="newField.type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                                <option value="texte">Texte</option>
                                <option value="email">Email</option>
                                <option value="date">Date</option>
                                <option value="long_texte">Zone de texte</option>
                                <option value="nombre">Nombre</option>
                                <option value="liste">Liste déroulante</option>
                                <option value="choix_multiple">Choix multiple</option>
                                <option value="champ_multiple">Champ multiple</option>
                                <option value="image">Image</option>
                                <option value="fichier">Fichier PDF</option>
                                <option value="geographique">Géographique</option>
                                <option value="choix_avec_condition">Choix avec condition</option>
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
                    <div v-if="newField.type === 'champ_multiple'"
                        class="mb-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <h5 class="font-medium text-purple-800 mb-2">
                            <i class="fas fa-info-circle mr-2"></i>Information sur le champ multiple
                        </h5>
                        <div class="text-sm text-purple-700">
                            <div>
                                <i class="fas fa-plus-circle mr-2"></i>L'utilisateur pourra ajouter plusieurs valeurs pour
                                ce champ
                            </div>
                            <div class="mt-1">
                                <i class="fas fa-list mr-2"></i>Les valeurs seront stockées séparées par des virgules
                            </div>
                        </div>
                    </div>

                    <!-- Options pour liste et choix multiple -->
                    <div v-if="newField.type === 'liste' || newField.type === 'choix_multiple'"
                        class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h5 class="font-medium text-blue-800 mb-3">
                            <i class="fas fa-list mr-2"></i>Configuration des options
                        </h5>
                        <div class="mb-3">
                            <div class="flex gap-2">
                                <input v-model="currentOption" type="text" placeholder="Tapez une option..."
                                    @keyup.enter="addOption"
                                    class="flex-1 px-3 py-2 border border-blue-300 rounded-lg focus:ring focus:ring-blue-300">
                                <button type="button" @click="addOption"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-plus mr-1"></i>Ajouter
                                </button>
                            </div>
                        </div>
                        <div v-if="newField.options.length > 0" class="space-y-2">
                            <p class="text-sm font-medium text-gray-700">Options ajoutées :</p>
                            <div class="flex flex-wrap gap-2">
                                <div v-for="(option, idx) in newField.options" :key="idx"
                                    class="flex items-center bg-white px-3 py-1 rounded-full border border-blue-300 text-sm">
                                    <span>@{{ option }}</span>
                                    <button type="button" @click="removeOption(idx)"
                                        class="ml-2 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-4 text-gray-500">
                            <i class="fas fa-info-circle mr-2"></i>Aucune option ajoutée pour le moment
                        </div>
                    </div>

                    <!-- Aperçu pour images et fichiers -->
                    <div v-if="newField.type === 'image' || newField.type === 'fichier'"
                        class="mb-4 p-4 bg-green-50 rounded-lg border border-green-200">
                        <h5 class="font-medium text-green-800 mb-2">
                            <i class="fas fa-info-circle mr-2"></i>Information sur le type de fichier
                        </h5>
                        <div class="text-sm text-green-700">
                            <div v-if="newField.type === 'image'">
                                <i class="fas fa-image mr-2"></i>Types acceptés : JPEG, PNG, JPG (max 5MB)
                            </div>
                            <div v-if="newField.type === 'fichier'">
                                <i class="fas fa-file-pdf mr-2"></i>Types acceptés : PDF uniquement (max 10MB)
                            </div>
                        </div>
                    </div>

                    @include('backOffice.components.offer-create-geoOption')

                    @include('backOffice.components.offer-create-choixCondition')

                    <button type="button" @click="addField"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Ajouter ce champ
                    </button>
                </div>
            </div>

            <!-- Bouton de soumission -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-save mr-2"></i>Enregistrer l'offre
                </button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    form: {
                        nom_enquete: '',
                        details_enquete: '',
                        date_limite: '',
                        priorite: 'moyenne',
                        status_offre: 'publiee',
                    },
                    formulaire: [],
                    newField: {
                        type: 'texte',
                        label: '',
                        obligation: false,
                        options: [],
                        constraint_level: 'all',
                        region_id: null,
                        district_id: null,
                        commune_id: null,
                        show_region: false,
                        show_district: false,
                        show_commune: false,
                        conditional_options: [],
                    },
                    currentConditionOption: '',
                    newCondition: {
                        type: 'texte',
                        label: '',
                        obligation: false,
                        options: []
                    },
                    newConditionOption: '',
                    currentOption: '',
                    regions: [],
                    districts: [],
                    communes: [],
                    selectedRegion: null,
                    selectedDistrict: null,
                    selectedCommune: null,
                    notyf: null,
                }
            },
            mounted() {
                this.notyf = new Notyf({
                    duration: 4000,
                    position: {
                        x: 'right',
                        y: 'top'
                    }
                });
                this.loadRegions();
                document.addEventListener('trix-change', () => {
                    this.form.details_enquete = document.querySelector('#details_enquete').value;
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
                    if (this.newField.constraint_level === 'commune') {
                        return this.newField.region_id && this.newField.district_id;
                    }
                    if (this.newField.constraint_level === 'district_commune') {
                        return this.newField.region_id;
                    }
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
                    }
                    if (this.newField.type !== 'liste' && this.newField.type !== 'choix_multiple') {
                        this.newField.options = [];
                        this.currentOption = '';
                    }
                    if (this.newField.type !== 'choix_avec_condition') {
                        this.newField.conditional_options = [];
                        this.currentConditionOption = '';
                        this.resetNewCondition();
                    }
                }
            },
            methods: {
                async loadRegions() {
                    try {
                        const response = await axios.get('{{ route('admin.regions') }}');
                        this.regions = response.data;
                    } catch (error) {
                        console.error('Erreur chargement régions', error);
                        this.notyf.error('Impossible de charger les régions');
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
                        this.communes = response.data;
                    } catch (error) {
                        console.error('Erreur chargement communes', error);
                        this.notyf.error('Impossible de charger les communes');
                    }
                },
                addConditionOption() {
                    if (this.currentConditionOption.trim()) {
                        this.newField.conditional_options.push({
                            label: this.currentConditionOption.trim(),
                            conditions: [],
                            showAddCondition: false
                        });
                        this.currentConditionOption = '';
                    }
                },

                removeConditionOption(optIndex) {
                    this.newField.conditional_options.splice(optIndex, 1);
                },

                showAddConditionForm(optIndex) {
                    // Fermer les autres formulaires
                    this.newField.conditional_options.forEach((opt, idx) => {
                        opt.showAddCondition = idx === optIndex;
                    });
                    // Réinitialiser la nouvelle condition
                    this.resetNewCondition();
                },

                addConditionToOption(optIndex) {
                    if (!this.newCondition.label.trim()) {
                        this.notyf.error('Le label de la condition est requis');
                        return;
                    }

                    if ((this.newCondition.type === 'liste' || this.newCondition.type === 'choix_multiple') &&
                        this.newCondition.options.length === 0) {
                        this.notyf.error('Ajoutez au moins une option pour ce type');
                        return;
                    }

                    const conditionToAdd = {
                        type: this.newCondition.type,
                        label: this.newCondition.label.trim(),
                        obligation: Boolean(this.newCondition.obligation),
                        options: [...this.newCondition.options]
                    };

                    this.newField.conditional_options[optIndex].conditions.push(conditionToAdd);
                    this.newField.conditional_options[optIndex].showAddCondition = false;
                    this.resetNewCondition();
                },

                removeCondition(optIndex, condIndex) {
                    this.newField.conditional_options[optIndex].conditions.splice(condIndex, 1);
                },

                addConditionOptionToNew() {
                    if (this.newConditionOption.trim() && !this.newCondition.options.includes(this
                            .newConditionOption.trim())) {
                        this.newCondition.options.push(this.newConditionOption.trim());
                        this.newConditionOption = '';
                    }
                },

                removeConditionOptionFromNew(index) {
                    this.newCondition.options.splice(index, 1);
                },

                resetNewCondition() {
                    this.newCondition = {
                        type: 'texte',
                        label: '',
                        obligation: false,
                        options: []
                    };
                    this.newConditionOption = '';
                },
                removeField(index) {
                    this.formulaire.splice(index, 1);
                },
                addOption() {
                    if (this.currentOption.trim() && !this.newField.options.includes(this.currentOption.trim())) {
                        this.newField.options.push(this.currentOption.trim());
                        this.currentOption = '';
                    }
                },
                removeOption(index) {
                    this.newField.options.splice(index, 1);
                },
                getGeoConfigSummary(field) {
                    if (field.type === 'choix_avec_condition') {
                        const optionsCount = field.conditional_options ? field.conditional_options.length : 0;
                        const conditionsCount = field.conditional_options ?
                            field.conditional_options.reduce((total, opt) => total + (opt.conditions ? opt
                                .conditions.length : 0), 0) : 0;
                        return `${optionsCount} option(s) avec ${conditionsCount} condition(s) au total`;
                    }
                    if (field.type === 'champ_multiple') {
                        return 'Champ à valeurs multiples';
                    }
                    if (field.constraint_level === 'all') return 'Sélection complète (Région, District, Commune)';
                    if (field.constraint_level === 'region_district') return 'Région + District';
                    if (field.constraint_level === 'region') return 'Région seule';
                    if (field.constraint_level === 'district' && !field.region_id)
                        return 'District seul (toutes régions)';
                    if (field.constraint_level === 'district' && field.region_id && !field.district_id)
                        return `Districts de la région ${this.regions.find(r => r.id === field.region_id)?.region || ''}`;
                    if (field.constraint_level === 'district' && field.district_id)
                        return `District ${this.districts.find(d => d.id === field.district_id)?.district || ''} (Région ${this.regions.find(r => r.id === field.region_id)?.region || ''} en lecture seule)`;
                    if (field.constraint_level === 'commune' && field.commune_id)
                        return `Commune ${this.communes.find(c => c.id === field.commune_id)?.commune || ''} (Région ${this.regions.find(r => r.id === field.region_id)?.region || ''}, District ${this.districts.find(d => d.id === field.district_id)?.district || ''} en lecture seule)`;
                    if (field.constraint_level === 'commune' && !field.commune_id)
                        return `Communes du district ${this.districts.find(d => d.id === field.district_id)?.district || ''} (Région ${this.regions.find(r => r.id === field.region_id)?.region || ''})`;
                    if (field.constraint_level === 'district_commune' && !field.region_id)
                        return 'District + Commune (toutes régions)';
                    if (field.constraint_level === 'district_commune' && field.region_id)
                        return `District + Commune (Région ${this.regions.find(r => r.id === field.region_id)?.region || ''} en lecture seule)`;
                    return 'Configuration incomplète';
                },
                addField() {
                    if (!this.newField.label.trim()) {
                        this.notyf.error('Le label est requis');
                        return;
                    }

                    if ((this.newField.type === 'liste' || this.newField.type === 'choix_multiple') &&
                        (!this.newField.options || this.newField.options.length === 0)) {
                        this.notyf.error('Ajoutez au moins une option');
                        return;
                    }
                    if (this.newField.type === 'choix_avec_condition') {
                        if (this.newField.conditional_options.length === 0) {
                            this.notyf.error('Ajoutez au moins une option avec condition');
                            return;
                        }

                        // Vérifier que chaque option a au moins une condition si nécessaire
                        const optionsWithoutConditions = this.newField.conditional_options.filter(opt =>
                            !opt.conditions || opt.conditions.length === 0
                        );

                        if (optionsWithoutConditions.length > 0) {
                            const shouldContinue = confirm(
                                `Les options suivantes n'ont pas de conditions: ${optionsWithoutConditions.map(opt => opt.label).join(', ')}. Continuer quand même ?`
                            );
                            if (!shouldContinue) return;
                        }
                    }
                    if (this.newField.type === 'geographique' && !this.isConfigurationValid) {
                        this.notyf.error('Veuillez compléter la configuration géographique');
                        return;
                    }

                    // Créer une copie profonde du champ pour éviter les références partagées
                    const fieldToAdd = {
                        type: this.newField.type,
                        label: this.newField.label.trim(),
                        obligation: Boolean(this.newField.obligation),
                        options: Array.isArray(this.newField.options) ? [...this.newField.options] : [],
                        conditional_options: this.newField.conditional_options ? JSON.parse(JSON.stringify(this
                            .newField.conditional_options)) : [],
                        constraint_level: this.newField.constraint_level,
                        region_id: this.newField.region_id,
                        district_id: this.newField.district_id,
                        commune_id: this.newField.commune_id,
                        show_region: this.newField.show_region,
                        show_district: this.newField.show_district,
                        show_commune: this.newField.show_commune
                    };

                    this.formulaire.push(fieldToAdd);
                    this.resetNewField();
                    this.notyf.success('Champ ajouté avec succès !');
                },

                resetNewField() {
                    this.newField = {
                        type: 'texte',
                        label: '',
                        obligation: false,
                        options: [],
                        conditional_options: [],
                        constraint_level: 'all',
                        region_id: null,
                        district_id: null,
                        commune_id: null,
                        show_region: false,
                        show_district: false,
                        show_commune: false,
                    };
                    this.currentOption = '';
                    this.currentConditionOption = '';
                    this.districts = [];
                    this.communes = [];
                    this.resetNewCondition();
                },
                async saveOffre() {
                    // Validation côté client
                    if (!this.form.nom_enquete.trim()) {
                        this.notyf.error("Le nom de l'enquête est requis.");
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
                        // Préparer les données exactement comme votre ancien code
                        const payload = {
                            form: {
                                nom_enquete: this.form.nom_enquete || '',
                                details_enquete: this.form.details_enquete || '',
                                date_debut: this.form.date_debut ||
                                    null, // Ajoutez cette ligne si vous avez un champ date_debut
                                date_limite: this.form.date_limite || '',
                                priorite: this.form.priorite || 'moyenne',
                                status_offre: this.form.status_offre || 'publiee'
                            },
                            formulaire: this.formulaire.map(field => {
                                // Créer l'objet de base pour chaque question
                                const questionData = {
                                    label: field.label || '',
                                    type: field.type || 'texte',
                                    obligation: Boolean(field.obligation)
                                };

                                // Ajouter les options si c'est une liste ou choix multiple
                                if ((field.type === 'liste' || field.type === 'choix_multiple') &&
                                    Array.isArray(field.options)) {
                                    questionData.options = field.options;
                                }

                                // Ajouter les données géographiques si c'est un champ géographique
                                if (field.type === 'geographique') {
                                    questionData.constraint_level = field.constraint_level || 'all';
                                    questionData.region_id = field.region_id || null;
                                    questionData.district_id = field.district_id || null;
                                    questionData.commune_id = field.commune_id || null;
                                }

                                if (field.type === 'choix_avec_condition' && Array.isArray(field
                                        .conditional_options)) {
                                    questionData.conditional_options = field.conditional_options;
                                }

                                return questionData;
                            })
                        };

                        console.log('Données envoyées:', payload); // Pour déboguer

                        const response = await axios.post('{{ route('admin.offers.store') }}', payload, {
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute('content') || ''
                            }
                        });

                        this.notyf.success('Offre créée avec succès !');
                        setTimeout(() => {
                            window.location.href = '{{ route('admin.offers') }}';
                        }, 1000);

                    } catch (error) {
                        console.error('Erreur complète:', error);
                        console.error('Réponse du serveur:', error.response?.data);

                        let errorMessage = 'Erreur lors de la création de l\'offre.';

                        if (error.response?.data?.error) {
                            errorMessage = error.response.data.error;
                        } else if (error.response?.data?.message) {
                            errorMessage = error.response.data.message;
                        } else if (error.response?.data?.messages) {
                            // Gestion des erreurs de validation
                            const errors = Object.values(error.response.data.messages).flat();
                            errorMessage = errors.join(', ');
                        }

                        this.notyf.error(errorMessage);
                    }
                }
            }
        }).mount('#app');
    </script>
@endsection
