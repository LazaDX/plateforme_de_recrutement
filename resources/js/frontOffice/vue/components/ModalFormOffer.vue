<template>
    <div>
        <section class="p-6">
            <div class="space-y-3">
                <button @click="showModal = true"
                    class="block w-full bg-gradient-to-r from-blue-600 to-green-500 hover:from-blue-700 hover:to-green-600 text-white py-4 px-4 rounded-2xl text-lg font-medium text-center transition-all duration-300 shadow-md hover:shadow-lg">
                    Postuler <i class="fas fa-paper-plane ml-2"></i>
                </button>
            </div>
        </section>

        <div v-show="showModal"
            class="fixed inset-0 z-[1000] bg-black bg-opacity-60 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out"
            :class="{ 'opacity穩0 pointer-events-none': !showModal, 'opacity穩100': showModal }">
            <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl transform transition-all duration-300 ease-in-out"
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
                    <form @submit.prevent="submitForm" ref="applicationForm">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <input type="hidden" name="offre_id" :value="offreId">
                        <div v-for="question in questions" :key="question.id" class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ question.label }}
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
                                <textarea v-model="responses[question.id].valeur"
                                    :name="'reponses[' + question.id + '][valeur]'"
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
                                <div v-if="question.all_regions && !question.region_id">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Région</label>
                                    <select v-model="responses[question.id].region_id"
                                        :name="'reponses[' + question.id + '][region_id]'"
                                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        @change="fetchDistricts(question.id)" :required="question.obligation">
                                        <option value="">Sélectionner une région...</option>
                                        <option v-for="region in regions" :value="region.id">
                                            {{ region.region }}
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
                                            {{ district.district }}
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

                                <div v-if="question.all_communes && !question.commune_id">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Commune</label>
                                    <select v-model="responses[question.id].commune_id"
                                        :name="'reponses[' + question.id + '][commune_id]'"
                                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        :disabled="!responses[question.id].district_id"
                                        :required="question.obligation && question.all_communes">
                                        <option value="">Sélectionner une commune...</option>
                                        <option v-for="commune in communes[question.id]" :value="commune.id">
                                            {{ commune.commune }}
                                        </option>
                                    </select>
                                </div>
                                <div v-else-if="question.commune_id">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Commune</label>
                                    <input sparked="true" type="text" :value="question.commune.commune || 'Non défini'"
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
                                {{ isSubmitting ? 'Envoi...' : prologue }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import modalFormOffer from './modalFormOffer.js';

    export default {
        props: {
            offre: {
                type: Object,
                required: true
            },
            regions: {
                type: Array,
                required: true
            },
            csrfToken: {
                type: String,
                required: true
            },
            districtsRoute: {
                type: String,
                required: true
            },
            communesRoute: {
                type: String,
                required: true
            },
            submitRoute: {
                type: String,
                required: true
            }
        },
        ...modalFormOffer
    };
</script>