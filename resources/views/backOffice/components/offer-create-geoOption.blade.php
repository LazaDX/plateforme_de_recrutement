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
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm" :disabled="!selectedRegion">
                        <option :value="null">Sélectionner un district...</option>
                        <option v-for="d in districts" :key="d.id" :value="d.id">
                            @{{ d.district }}</option>
                    </select>
                </div>
                <div v-if="newField.constraint_level === 'all'">
                    <label class="block text-xs text-gray-600 mb-1">Commune</label>
                    <select v-model="selectedCommune" class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
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
                    <select v-model="selectedDistrict" class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                        :disabled="!selectedRegion">
                        <option :value="null">Sélectionner un district...</option>
                        <option v-for="d in districts" :key="d.id" :value="d.id">
                            @{{ d.district }}</option>
                    </select>
                </div>
                <!-- Région seule -->
                <div v-if="newField.constraint_level === 'region'">
                    <label class="block text-xs text-gray-600 mb-1">Région</label>
                    <select v-model="selectedRegion" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
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
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100" readonly>
                </div>
                <div
                    v-if="newField.show_district && newField.constraint_level !== 'all' && newField.constraint_level !== 'region_district'">
                    <label class="block text-xs text-gray-600 mb-1">District</label>
                    <input v-if="newField.district_id" type="text" :value="getSelectedDistrictName"
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100" readonly>
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
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100" readonly>
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
            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'all' }">
                <input type="radio" v-model="newField.constraint_level" value="all" class="mt-1">
                <div>
                    <div class="font-medium text-gray-700">Sélection complète</div>
                    <div class="text-sm text-gray-500">L'utilisateur choisit région, district et
                        commune
                        via combobox</div>
                </div>
            </label>
            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'region_district' }">
                <input type="radio" v-model="newField.constraint_level" value="region_district" class="mt-1">
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
                    <div class="text-sm text-gray-500">L'utilisateur choisit parmi toutes les régions
                        via
                        combobox</div>
                </div>
            </label>
            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'district' }">
                <input type="radio" v-model="newField.constraint_level" value="district" class="mt-1">
                <div>
                    <div class="font-medium text-gray-700">District seul</div>
                    <div class="text-sm text-gray-500">L'utilisateur choisit un district, région en
                        lecture
                        seule si spécifiée</div>
                </div>
            </label>
            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'commune' }">
                <input type="radio" v-model="newField.constraint_level" value="commune" class="mt-1">
                <div>
                    <div class="font-medium text-gray-700">Commune seule</div>
                    <div class="text-sm text-gray-500">L'utilisateur choisit une commune, région et
                        district en lecture seule</div>
                </div>
            </label>
            <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                :class="{ 'bg-blue-50 border-blue-300': newField.constraint_level === 'district_commune' }">
                <input type="radio" v-model="newField.constraint_level" value="district_commune" class="mt-1">
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
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" required>
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
    <div v-if="newField.constraint_level === 'district_commune'" class="mb-4 p-4 bg-gray-50 rounded-lg">
        <h5 class="font-medium text-gray-700 mb-3">Configuration pour district + commune</h5>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Région
                    (obligatoire)</label>
                <select v-model="newField.region_id" @change="loadDistricts(newField.region_id)"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" required>
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
            <div v-if="newField.constraint_level === 'district' && newField.region_id && !newField.district_id">
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
