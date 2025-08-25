<!-- Configuration pour choix avec condition -->
<div v-if="newField.type === 'choix_avec_condition'" class="mb-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
    <h5 class="font-medium text-purple-800 mb-3">
        <i class="fas fa-code-branch mr-2"></i>Configuration des choix avec conditions
    </h5>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Options principales</label>
        <div class="flex gap-2 mb-3">
            <input v-model="currentConditionOption" type="text" placeholder="Tapez une option..."
                @keyup.enter="addConditionOption"
                class="flex-1 px-3 py-2 border border-purple-300 rounded-lg focus:ring focus:ring-purple-300">
            <button type="button" @click="addConditionOption"
                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                <i class="fas fa-plus mr-1"></i>Ajouter
            </button>
        </div>
    </div>

    <!-- Liste des options avec leurs conditions -->
    <div v-if="newField.conditional_options.length > 0" class="space-y-4">
        <div v-for="(option, optIndex) in newField.conditional_options" :key="optIndex"
            class="bg-white p-4 rounded-lg border border-purple-200">
            <div class="flex justify-between items-start mb-3">
                <h6 class="font-medium text-gray-800 flex items-center">
                    <i class="fas fa-tag mr-2 text-purple-600"></i>
                    Option: "@{{ option.label }}"
                </h6>
                <button type="button" @click="removeConditionOption(optIndex)"
                    class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <!-- Conditions pour cette option -->
            <div v-if="option.conditions.length > 0" class="space-y-3 mb-3">
                <div v-for="(condition, condIndex) in option.conditions" :key="condIndex"
                    class="bg-gray-50 p-3 rounded border border-gray-200">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-2">
                            <div>
                                <span class="text-xs text-gray-500">Label:</span>
                                <p class="font-medium">@{{ condition.label }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Type:</span>
                                <p class="font-medium">@{{ condition.type }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Obligatoire:</span>
                                <p class="font-medium">@{{ condition.obligation ? 'Oui' : 'Non' }}</p>
                            </div>
                        </div>
                        <button type="button" @click="removeCondition(optIndex, condIndex)"
                            class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50 ml-2">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div v-if="condition.options && condition.options.length > 0" class="text-xs text-gray-600">
                        Options: @{{ condition.options.join(', ') }}
                    </div>
                </div>
            </div>

            <!-- Ajouter une condition à cette option -->
            <div v-if="option.showAddCondition" class="bg-blue-50 p-3 rounded border border-blue-200 mt-3">
                <h6 class="font-medium text-blue-800 mb-3">Ajouter une condition</h6>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Type</label>
                        <select v-model="newCondition.type"
                            class="w-full px-2 py-1 text-sm border border-blue-300 rounded focus:ring focus:ring-blue-300">
                            <option value="texte">Texte</option>
                            <option value="email">Email</option>
                            <option value="date">Date</option>
                            <option value="long_texte">Zone de texte</option>
                            <option value="nombre">Nombre</option>
                            <option value="liste">Liste déroulante</option>
                            <option value="choix_multiple">Choix multiple</option>
                            <option value="image">Image</option>
                            <option value="fichier">Fichier PDF</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Label</label>
                        <input v-model="newCondition.label" type="text"
                            class="w-full px-2 py-1 text-sm border border-blue-300 rounded focus:ring focus:ring-blue-300"
                            placeholder="Label de la condition">
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center">
                            <input v-model="newCondition.obligation" type="checkbox" class="mr-1">
                            <span class="text-xs text-gray-700">Obligatoire</span>
                        </label>
                    </div>
                    <div class="flex items-end">
                        <button type="button" @click="addConditionToOption(optIndex)"
                            class="w-full px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            <i class="fas fa-plus mr-1"></i>Ajouter
                        </button>
                    </div>
                </div>

                <!-- Options pour liste et choix multiple dans les conditions -->
                <div v-if="newCondition.type === 'liste' || newCondition.type === 'choix_multiple'"
                    class="mb-3 p-3 bg-white rounded border border-blue-300">
                    <h6 class="font-medium text-blue-700 mb-2">Options pour @{{ newCondition.type }}</h6>
                    <div class="flex gap-2 mb-2">
                        <input v-model="newConditionOption" type="text" placeholder="Option..."
                            @keyup.enter="addConditionOptionToNew"
                            class="flex-1 px-2 py-1 text-sm border border-blue-300 rounded">
                        <button type="button" @click="addConditionOptionToNew"
                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div v-if="newCondition.options.length > 0" class="flex flex-wrap gap-1">
                        <span v-for="(opt, idx) in newCondition.options" :key="idx"
                            class="inline-flex items-center bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                            @{{ opt }}
                            <button type="button" @click="removeConditionOptionFromNew(idx)"
                                class="ml-1 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    </div>
                </div>

                {{-- <div v-if="field.options && (field.type === 'choix_multiple' || field.type === 'liste')"
                    class="text-xs text-gray-600 mt-1">
                    Options: @{{ field.options.join(', ') }}
                </div>
                <div v-if="field.type === 'choix_avec_condition' && field.conditional_options"
                    class="text-xs text-gray-600 mt-1">
                    Configuration: @{{ getGeoConfigSummary(field) }}
                    <div class="mt-1 space-y-1">
                        <div v-for="option in field.conditional_options" :key="option.label"
                            class="text-xs bg-purple-50 p-2 rounded border border-purple-200">
                            <strong>@{{ option.label }}</strong>
                            <span v-if="option.conditions && option.conditions.length > 0">
                                : @{{ option.conditions.length }} condition(s)
                                <span class="text-purple-600">
                                    (@{{ option.conditions.map(c => c.type).join(', ') }})
                                </span>
                            </span>
                        </div>
                    </div>
                </div> --}}


                <div class="flex gap-2">
                    <button type="button" @click="option.showAddCondition = false"
                        class="px-3 py-1 border border-gray-300 text-gray-700 text-sm rounded hover:bg-gray-50">
                        Annuler
                    </button>
                </div>
            </div>

            <button v-if="!option.showAddCondition" type="button" @click="showAddConditionForm(optIndex)"
                class="mt-2 px-3 py-1 border-2 border-dashed border-purple-300 text-purple-600 text-sm rounded hover:border-purple-400 hover:bg-purple-50">
                <i class="fas fa-plus mr-1"></i>Ajouter une condition
            </button>
        </div>
    </div>

    <div v-if="newField.conditional_options.length === 0" class="text-center py-4 text-gray-500">
        <i class="fas fa-info-circle mr-2"></i>Aucune option ajoutée pour le moment
    </div>
</div>
