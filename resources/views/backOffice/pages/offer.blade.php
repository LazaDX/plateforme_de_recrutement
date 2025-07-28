@extends('backOffice.layouts.admin')

@section('title', 'Offres d\'enquêtes')

@section('content')
<div id="app" class="container mx-auto p-6">

    <h1 class="text-xl font-bold mb-4">Offres d'enquêtes</h1>

    <button @click="open = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Nouvelle offre
    </button>

    <!-- MODALE -->
    <div v-if="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-auto">
        <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg relative max-h-[90vh] overflow-auto">
            <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

            <h2 class="text-xl font-semibold mb-4">Créer une nouvelle offre</h2>

            <form>

                <!-- Nom de l'enquête -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'enquête</label>
                    <input v-model="form.nom_enquete" type="text" name="nom_enquete" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" />
                </div>

                <!-- Détails de l'enquête -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Détails de l'enquête</label>
                    <textarea v-model="form.details_enquete" name="details_enquete" rows="4" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300"></textarea>
                </div>

                <!-- Date de début -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input v-model="form.date_debut" type="date" name="date_debut" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" />
                </div>

                <!-- Date limite (équivalent dateExpiration) -->
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

                {{-- <!-- Priorité -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                    <select v-model="form.priorite" name="priorite"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                        <option value="basse">Basse</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="haute">Haute</option>
                    </select>
                </div> --}}

                <!-- Champs dynamiques (Questions formulaire) -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Questions du formulaire de candidature</h3>

                    <div v-for="(field, index) in formulaire" :key="index" class="p-3 bg-gray-50 rounded flex justify-between items-center mb-3">
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
                            <select v-model="newField.type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                                <option value="text">Texte</option>
                                <option value="email">Email</option>
                                <option value="tel">Téléphone</option>
                                <option value="textarea">Zone de texte</option>
                                <option value="date">Date</option>
                                <option value="file">Fichier</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                            <input v-model="newField.label" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" placeholder="Nom du champ" />
                        </div>
                        <div class="flex items-end">
                            <label class="inline-flex items-center">
                                <input v-model="newField.obligation" type="checkbox" class="mr-2" />
                                <span class="text-sm text-gray-700">Obligatoire</span>
                            </label>
                        </div>
                    </div>

                    <button type="button" @click="addField" class="mt-4 bg-gray-100 text-gray-800 px-4 py-2 rounded hover:bg-gray-200 flex items-center">
                        + Ajouter une question
                    </button>
                </div>

                <!-- Champs cachés générés -->
                <div v-for="(field, index) in formulaire" :key="'input-'+index">
                    <input type="hidden" :name="`questions_formulaires[${index}][type]`" :value="field.type" />
                    <input type="hidden" :name="`questions_formulaires[${index}][label]`" :value="field.label" />
                    <input type="hidden" :name="`questions_formulaires[${index}][obligation]`" :value="field.obligation ? '1' : '0'" />
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" @click="saveOffre">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Inclure Vue depuis CDN -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

<script>
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                open: false,
                form: {
                    nom_enquete: '',
                    details_enquete: '',
                    date_debut: '',
                    date_limite: '',
                    status_offre: 'brouillon',
                    priorite: 'moyenne',
                },
                formulaire: [],
                newField: {
                    type: 'text',
                    label: '',
                    obligation: false,
                }
            }
        },
        methods: {
            addField() {
                if (!this.newField.label.trim()) {
                    alert('Le label de la question est requis');
                    return;
                }
                this.formulaire.push({...this.newField});
                this.newField.label = '';
                this.newField.obligation = false;
            },
            removeField(index) {
                this.formulaire.splice(index, 1);
            },
            async saveOffre()
            {

                if (this.formulaire.length === 0) {
                    alert("Ajoutez au moins une question.");
                    return;
                }
                
                try {
                    const response = await axios.post('/api/offre/create_offres', {
                    form: this.form,
                    formulaire: this.formulaire
                    });
                    console.log('Offre enregistrée', response.data);
                } catch (error) {
                    console.error('Erreur lors de l\'enregistrement', error.response?.data || error.message);
                }
            },
            resetForm() {
                this.form = {
                    nom_enquete: '',
                    details_enquete: '',
                    date_debut: '',
                    date_limite: '',
                    status_offre: 'brouillon',
                    // priorite: 'moyenne',
                    administrateur_id: 1
                };
                this.formulaire = [];
                this.newField = {
                    type: 'text',
                    label: '',
                    obligation: false,
                };
            }
        }
    }).mount('#app');
</script>
@endsection
