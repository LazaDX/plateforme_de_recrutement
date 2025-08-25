<!-- Choix avec condition -->

<div class="space-y-3">
    <div v-for="option in question.conditional_options_array" :key="option.label"
        class="border border-gray-300 rounded-lg p-4 hover:bg-gray-50 transition-colors">
        <label class="flex items-start cursor-pointer">
            <input type="radio" :value="option.label" v-model="responses[question.id].selected_main_option"
                @change="handleMainOptionChange(question.id, option.label)"
                class="mt-1 mr-3 text-blue-600 focus:ring-blue-500" :name="'main_option_' + question.id">
            <div class="flex-1">
                <span class="font-medium text-gray-700">@{{ option.label }}</span>

                <!-- Conditions pour cette option -->
                <div v-if="responses[question.id].selected_main_option === option.label && option.conditions.length > 0"
                    class="mt-4 pl-4 border-l-4 border-blue-200 bg-blue-50 rounded-r-lg p-4 space-y-4">
                    <h5 class="font-medium text-blue-800 mb-3">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Informations supplémentaires requises :
                    </h5>

                    <div v-for="condition in option.conditions" :key="condition.id || condition.label"
                        class="bg-white rounded-lg p-4 border border-blue-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            @{{ condition.label }}
                            <span v-if="condition.obligation" class="text-red-500">*</span>
                        </label>

                        <!-- Condition: Texte -->
                        <div v-if="condition.type === 'texte'" class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-font"></i>
                            </div>
                            <input type="text"
                                v-model="responses[question.id].conditions[option.label + '_' + condition.label]"
                                :name="'reponses[' + question.id + '][conditions][' + option.label + '_' + condition
                                    .label + ']'"
                                class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                :required="condition.obligation && responses[question.id].selected_main_option === option.label">
                        </div>

                        <!-- Condition: Email -->
                        <div v-else-if="condition.type === 'email'" class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email"
                                v-model="responses[question.id].conditions[option.label + '_' + condition.label]"
                                :name="'reponses[' + question.id + '][conditions][' + option.label + '_' + condition
                                    .label + ']'"
                                class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                :required="condition.obligation && responses[question.id].selected_main_option === option.label">
                        </div>

                        <!-- Condition: Zone de texte -->
                        <div v-else-if="condition.type === 'long_texte'" class="relative">
                            <div class="absolute top-2 left-0 pl-3 flex items-start pointer-events-none text-gray-400">
                                <i class="fas fa-align-left"></i>
                            </div>
                            <textarea v-model="responses[question.id].conditions[option.label + '_' + condition.label]"
                                :name="'reponses[' + question.id + '][conditions][' + option.label + '_' + condition
                                    .label + ']'"
                                class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                rows="3" :required="condition.obligation && responses[question.id].selected_main_option === option.label"></textarea>
                        </div>

                        <!-- Condition: Nombre -->
                        <div v-else-if="condition.type === 'nombre'" class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <input type="number"
                                v-model.number="responses[question.id].conditions[option.label + '_' + condition.label]"
                                :name="'reponses[' + question.id + '][conditions][' + option.label + '_' + condition
                                    .label + ']'"
                                class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                :required="condition.obligation && responses[question.id].selected_main_option === option.label">
                        </div>

                        <!-- Condition: Date -->
                        <div v-else-if="condition.type === 'date'" class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <input type="date"
                                v-model="responses[question.id].conditions[option.label + '_' + condition.label]"
                                :name="'reponses[' + question.id + '][conditions][' + option.label + '_' + condition
                                    .label + ']'"
                                class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                :required="condition.obligation && responses[question.id].selected_main_option === option.label">
                        </div>

                        <!-- Condition: Liste déroulante -->
                        <div v-else-if="condition.type === 'liste'" class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-list"></i>
                            </div>
                            <select v-model="responses[question.id].conditions[option.label + '_' + condition.label]"
                                :name="'reponses[' + question.id + '][conditions][' + option.label + '_' + condition
                                    .label + ']'"
                                class="pl-10 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none"
                                :required="condition.obligation && responses[question.id].selected_main_option === option.label">
                                <option value="">Sélectionnez une option...</option>
                                <option v-for="opt in condition.options" :key="opt"
                                    :value="opt">
                                    @{{ opt }}
                                </option>
                            </select>
                        </div>

                        <!-- Condition: Choix multiple -->
                        <div v-else-if="condition.type === 'choix_multiple'" class="space-y-2">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <label v-for="opt in condition.options" :key="opt"
                                    class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" :value="opt"
                                        v-model="responses[question.id].condition_multiple[option.label + '_' + condition.label]"
                                        class="mr-3 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">@{{ opt }}</span>
                                </label>
                            </div>
                            <input type="hidden"
                                :name="'reponses[' + question.id + '][conditions][' + option.label + '_' + condition
                                    .label + ']'"
                                :value="(responses[question.id].condition_multiple[option.label + '_' + condition.label] || [])
                                .join(', ')">
                        </div>

                        <!-- Condition: Image -->
                        <div v-else-if="condition.type === 'image'" class="space-y-3">
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                                <div
                                    v-if="!responses[question.id].condition_files[option.label + '_' + condition.label + '_preview']">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-600 text-sm">Image pour @{{ condition.label }}</p>
                                    <p class="text-xs text-gray-500">JPEG, PNG, JPG (max 5MB)</p>
                                </div>
                                <div v-else class="space-y-2">
                                    <img :src="responses[question.id].condition_files[option.label + '_' + condition.label +
                                        '_preview']"
                                        alt="Aperçu" class="max-w-32 max-h-32 mx-auto rounded-lg shadow-md">
                                    <p class="text-xs text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>Image sélectionnée
                                    </p>
                                </div>
                                <input type="file"
                                    :ref="'condition_file_' + question.id + '_' + option.label + '_' + condition.label"
                                    @change="handleConditionFileUpload(question.id, option.label, condition.label, condition.type, $event)"
                                    accept="image/jpeg,image/png,image/jpg" class="hidden"
                                    :required="condition.obligation && responses[question.id].selected_main_option === option
                                        .label && !responses[question.id].condition_files[option.label + '_' +
                                            condition.label]">
                            </div>
                            <button type="button"
                                @click="$refs['condition_file_' + question.id + '_' + option.label + '_' + condition.label][0].click()"
                                class="w-full py-2 px-4 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors text-sm">
                                <i class="fas fa-image mr-2"></i>Choisir une image
                            </button>
                        </div>

                        <!-- Condition: Fichier PDF -->
                        <div v-else-if="condition.type === 'fichier'" class="space-y-3">
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-red-400 transition-colors">
                                <div
                                    v-if="!responses[question.id].condition_files[option.label + '_' + condition.label + '_name']">
                                    <i class="fas fa-file-pdf text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-600 text-sm">PDF pour @{{ condition.label }}</p>
                                    <p class="text-xs text-gray-500">PDF uniquement (max 10MB)</p>
                                </div>
                                <div v-else class="space-y-2">
                                    <i class="fas fa-file-pdf text-2xl text-red-600"></i>
                                    <p class="text-xs text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        @{{ responses[question.id].condition_files[option.label + '_' + condition.label + '_name'] }}
                                    </p>
                                </div>
                                <input type="file"
                                    :ref="'condition_file_' + question.id + '_' + option.label + '_' + condition.label"
                                    @change="handleConditionFileUpload(question.id, option.label, condition.label, condition.type, $event)"
                                    accept="application/pdf" class="hidden"
                                    :required="condition.obligation && responses[question.id].selected_main_option === option
                                        .label && !responses[question.id].condition_files[option.label + '_' +
                                            condition.label]">
                            </div>
                            <button type="button"
                                @click="$refs['condition_file_' + question.id + '_' + option.label + '_' + condition.label][0].click()"
                                class="w-full py-2 px-4 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition-colors text-sm">
                                <i class="fas fa-file-pdf mr-2"></i>Choisir un fichier PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </label>
    </div>
</div>

<!-- Input caché pour stocker la sélection principale -->
<input type="hidden" :name="'reponses[' + question.id + '][valeur]'"
    :value="responses[question.id].selected_main_option">
