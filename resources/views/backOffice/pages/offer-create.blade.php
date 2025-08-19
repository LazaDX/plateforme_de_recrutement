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

                    <!-- Options Géographiques -->
                    <div v-if="newField.type === 'geographique'" class="mb-4 p-4 bg-gray-100 rounded">
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
                                    <!-- Région seule -->
                                    <div v-if="newField.constraint_level === 'region'">
                                        <label class="block text-xs text-gray-600 mb-1">Région</label>
                                        <select v-model="selectedRegion"
                                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                            <option :value="null">Sélectionner une région...</option>
                                            <option v-for="r in regions" :key="r.id" :value="r.id">
                                                @{{ r.region }}</option>
                                        </select>
                                    </div>
                                    <!-- Affichage en lecture seule -->
                                    <div
                                        v-if="newField.region_id && newField.show_region && newField.constraint_level !== 'all' && newField.constraint_level !== 'region_district' && newField.constraint_level !== 'region'">
                                        <label class="block text-xs text-gray-600 mb-1">Région</label>
                                        <input type="text" :value="getSelectedRegionName"
                                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                                            readonly>
                                    </div>
                                    <div
                                        v-if="newField.show_district && newField.constraint_level !== 'all' && newField.constraint_level !== 'region_district'">
                                        <label class="block text-xs text-gray-600 mb-1">District</label>
                                        <input v-if="newField.district_id" type="text"
                                            :value="getSelectedDistrictName"
                                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                                            readonly>
                                        <select v-else class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                                            :disabled="!newField.region_id">
                                            <option :value="null">Sélectionner un district...</option>
                                            <option v-for="d in districts" :key="d.id" :value="d.id">
                                                @{{ d.district }}</option>
                                        </select>
                                    </div>
                                    <div v-if="newField.show_commune && newField.constraint_level !== 'all'">
                                        <label class="block text-xs text-gray-600 mb-1">Commune</label>
                                        <input v-if="newField.commune_id" type="text" :value="getSelectedCommuneName"
                                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                                            readonly>
                                        <select v-else v-model="selectedCommune"
                                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                                            :disabled="!newField.district_id">
                                            <option :value="null">Sélectionner une commune...</option>
                                            <option v-for="c in communes" :key="c.id" :value="c.id">
                                                @{{ c.commune }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Niveau de contrainte -->
                        <div class="mb-6">
                            <h4 class="text-lg font-medium text-gray-800 mb-3">Options de sélection géographique</h4>
                            <div class="space-y-3">
                                <label
                                    class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                    :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'all' }">
                                    <input type="radio" v-model="newField.constraint_level" value="all"
                                        class="mt-1">
                                    <div>
                                        <div class="font-medium text-gray-700">Sélection complète</div>
                                        <div class="text-sm text-gray-500">L'utilisateur choisit région, district et
                                            commune
                                            via combobox</div>
                                    </div>
                                </label>
                                <label
                                    class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                    :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'region_district' }">
                                    <input type="radio" v-model="newField.constraint_level" value="region_district"
                                        class="mt-1">
                                    <div>
                                        <div class="font-medium text-gray-700">Région + District</div>
                                        <div class="text-sm text-gray-500">L'utilisateur choisit région et district via
                                            combobox</div>
                                    </div>
                                </label>
                                <label
                                    class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                    :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'region' }">
                                    <input type="radio" v-model="newField.constraint_level" value="region"
                                        class="mt-1">
                                    <div>
                                        <div class="font-medium text-gray-700">Région seule</div>
                                        <div class="text-sm text-gray-500">L'utilisateur choisit parmi toutes les régions
                                            via
                                            combobox</div>
                                    </div>
                                </label>
                                <label
                                    class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                    :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'district' }">
                                    <input type="radio" v-model="newField.constraint_level" value="district"
                                        class="mt-1">
                                    <div>
                                        <div class="font-medium text-gray-700">District seul</div>
                                        <div class="text-sm text-gray-500">L'utilisateur choisit un district, région en
                                            lecture
                                            seule si spécifiée</div>
                                    </div>
                                </label>
                                <label
                                    class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                    :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'commune' }">
                                    <input type="radio" v-model="newField.constraint_level" value="commune"
                                        class="mt-1">
                                    <div>
                                        <div class="font-medium text-gray-700">Commune seule</div>
                                        <div class="text-sm text-gray-500">L'utilisateur choisit une commune, région et
                                            district en lecture seule</div>
                                    </div>
                                </label>
                                <label
                                    class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                    :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'district_commune' }">
                                    <input type="radio" v-model="newField.constraint_level" value="district_commune"
                                        class="mt-1">
                                    <div>
                                        <div class="font-medium text-gray-700">District + Commune</div>
                                        <div class="text-sm text-gray-500">L'utilisateur choisit district et commune,
                                            région en
                                            lecture seule</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Configuration spécifique pour district -->
                        <div v-if="newField.constraint_level === 'district'" class="mb-4 p-4 bg-gray-50 rounded-lg">
                            <h5 class="font-medium text-gray-700 mb-3">Configuration pour district seul</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Région
                                        (optionnelle)</label>
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

                        <!-- Configuration spécifique pour commune -->
                        <div v-if="newField.constraint_level === 'commune'" class="mb-4 p-4 bg-gray-50 rounded-lg">
                            <h5 class="font-medium text-gray-700 mb-3">Configuration pour commune seule</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Région
                                        (obligatoire)</label>
                                    <select v-model="newField.region_id" @change="loadDistricts(newField.region_id)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300"
                                        required>
                                        <option :value="null" disabled>Sélectionner une région...</option>
                                        <option v-for="r in regions" :key="r.id" :value="r.id">
                                            @{{ r.region }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">District
                                        (obligatoire)</label>
                                    <select v-model="newField.district_id" @change="loadCommunes(newField.district_id)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300"
                                        :disabled="!newField.region_id" required>
                                        <option :value="null" disabled>Sélectionner un district...</option>
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

                        <!-- Configuration spécifique pour district + commune -->
                        <div v-if="newField.constraint_level === 'district_commune'"
                            class="mb-4 p-4 bg-gray-50 rounded-lg">
                            <h5 class="font-medium text-gray-700 mb-3">Configuration pour district + commune</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Région
                                        (obligatoire)</label>
                                    <select v-model="newField.region_id" @change="loadDistricts(newField.region_id)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300"
                                        required>
                                        <option :value="null" disabled>Sélectionner une région...</option>
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
                                <div v-if="newField.constraint_level === 'region_district'">
                                    L'utilisateur choisira région et district via combobox.
                                </div>
                                <div v-if="newField.constraint_level === 'region'">
                                    L'utilisateur choisira parmi toutes les régions via combobox.
                                </div>
                                <div v-if="newField.constraint_level === 'district' && !newField.region_id">
                                    L'utilisateur choisira parmi tous les districts, sans région spécifiée.
                                </div>
                                <div
                                    v-if="newField.constraint_level === 'district' && newField.region_id && !newField.district_id">
                                    L'utilisateur choisira parmi les districts de la région
                                    "<strong>@{{ getSelectedRegionName }}</strong>".
                                </div>
                                <div v-if="newField.constraint_level === 'district' && newField.district_id">
                                    L'utilisateur verra la région "<strong>@{{ getSelectedRegionName }}</strong>" en lecture
                                    seule et
                                    choisira le district "<strong>@{{ getSelectedDistrictName }}</strong>".
                                </div>
                                <div v-if="newField.constraint_level === 'commune' && newField.commune_id">
                                    L'utilisateur verra la région "<strong>@{{ getSelectedRegionName }}</strong>" et le district
                                    "<strong>@{{ getSelectedDistrictName }}</strong>" en lecture seule, et choisira la commune
                                    "<strong>@{{ getSelectedCommuneName }}</strong>".
                                </div>
                                <div v-if="newField.constraint_level === 'commune' && !newField.commune_id">
                                    L'utilisateur choisira parmi les communes du district
                                    "<strong>@{{ getSelectedDistrictName }}</strong>" de la région
                                    "<strong>@{{ getSelectedRegionName }}</strong>".
                                </div>
                                <div v-if="newField.constraint_level === 'district_commune' && !newField.region_id">
                                    L'utilisateur choisira district et commune via combobox, sans région spécifiée.
                                </div>
                                <div v-if="newField.constraint_level === 'district_commune' && newField.region_id">
                                    L'utilisateur verra la région "<strong>@{{ getSelectedRegionName }}</strong>" en lecture
                                    seule et
                                    choisira district et commune via combobox.
                                </div>
                                <div v-else class="text-orange-600">
                                    ⚠️ Configuration incomplète - veuillez sélectionner les options nécessaires.
                                </div>
                            </div>
                        </div>
                    </div>

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
                    },
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
                },

                resetNewField() {
                    this.newField = {
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
                    };
                    this.currentOption = '';
                    this.districts = [];
                    this.communes = [];
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
