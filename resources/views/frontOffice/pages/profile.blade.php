@extends('frontOffice.layouts.app')

@section('title', 'Mon profile')

@section('content')
    <div id="profile-app" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Profile Summary -->
            <div class="p-6 bg-white rounded-lg shadow">
                <div class="text-center">
                    <div class="relative mb-4">
                        <img v-if="previewAvatar" :src="previewAvatar" alt="Profile"
                            class="w-24 h-24 rounded-full mx-auto object-cover" />
                        <div v-else class="w-24 h-24 bg-gray-100 rounded-full mx-auto flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 text-3xl"></i>
                        </div>
                        <button @click="triggerFileInput"
                            class="absolute bottom-0 right-1/2 transform translate-x-1/2 translate-y-1/2 bg-gray-900 text-white p-2 rounded-full hover:bg-gray-800 transition-colors">
                            <i class="fas fa-upload text-xs"></i>
                        </button>
                        <input type="file" id="photo-input" @change="handleFileChange"
                            accept="image/jpeg,image/jpg,image/png" class="hidden" />
                    </div>

                    <h2 class="text-xl font-semibold text-gray-900 mb-1">@{{ formData.nom }} @{{ formData.prenom }}</h2>
                    <p class="text-gray-600 mb-2">@{{ formData.email || 'Non spécifié' }}</p>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-envelope mr-2"></i>
                        <span>@{{ formData.email }}</span>
                    </div>
                    {{-- <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-phone mr-2"></i>
                        <span>@{{ formData.phone || 'Non spécifié' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-pin mr-2"></i>
                        <span>@{{ formData.location || 'Non spécifié' }}</span>
                    </div> --}}
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>@{{ memberSince }}</span>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="lg:col-span-3 p-6 bg-white rounded-lg shadow">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Informations du profil</h3>
                    <button @click="isEditing ? handleSave() : isEditing = true"
                        :class="['inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium', isEditing ?
                            'bg-gray-900 text-white hover:bg-gray-800' :
                            'border border-gray-300 text-gray-700 hover:bg-gray-50'
                        ]">
                        <i class="fas fa-edit mr-2"></i>
                        @{{ isEditing ? 'Enregistrer les modifications' : 'Modifier le profil' }}
                    </button>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input type="text" v-model="formData.nom" :disabled="!isEditing"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 disabled:bg-gray-50" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input type="text" v-model="formData.prenom" :disabled="!isEditing"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 disabled:bg-gray-50" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" v-model="formData.email" :disabled="!isEditing"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 disabled:bg-gray-50" />
                        </div>

                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="tel" v-model="formData.phone" :disabled="!isEditing"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 disabled:bg-gray-50" />
                        </div> --}}

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                            <input type="date" v-model="formData.date_de_naissance" :disabled="!isEditing"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 disabled:bg-gray-50" />
                        </div>

                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                            <input type="text" v-model="formData.adresse" :disabled="!isEditing"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 disabled:bg-gray-50" />
                        </div> --}}
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diplômes</label>
                        <textarea v-model="formData.diplomes" :disabled="!isEditing" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 disabled:bg-gray-50"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expériences</label>
                        <textarea v-model="formData.experiences" :disabled="!isEditing" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 disabled:bg-gray-50"></textarea>
                    </div>
                </div>

                <div v-if="isEditing" class="flex items-center justify-end space-x-4 mt-6 pt-6 border-t border-gray-200">
                    <button @click="isEditing = false"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button @click="handleSave"
                        class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800">
                        Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclure FontAwesome, Vue.js, et Notyf -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <script>
        const {
            createApp,
            ref,
            computed
        } = Vue;
        const notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'top'
            },
            types: [{
                type: 'error',
                background: '#EF4444',
                icon: {
                    className: 'fas fa-exclamation-circle',
                    tagName: 'i',
                    color: 'white'
                }
            }]
        });

        const app = createApp({
            setup() {
                const enqueteur = ref(@json($enqueteur));
                const isEditing = ref(false);
                const formData = ref({
                    nom: enqueteur.value.nom || '',
                    prenom: enqueteur.value.prenom || '',
                    email: enqueteur.value.email || '',
                    phone: enqueteur.value.phone || '',
                    location: enqueteur.value.location || '',
                    date_de_naissance: enqueteur.value.date_de_naissance || '',
                    diplomes: enqueteur.value.diplomes || '',
                    experiences: enqueteur.value.experiences || '',
                });
                const selectedFile = ref(null);
                const previewAvatar = ref(enqueteur.value.photo ? '/storage/' + enqueteur
                    .value.photo : null);

                const handleInputChange = (field, value) => {
                    formData.value[field] = value;
                };

                const triggerFileInput = () => {
                    document.getElementById('photo-input').click();
                };

                const handleFileChange = (event) => {
                    const file = event.target.files[0];
                    if (file) {
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                        if (!validTypes.includes(file.type)) {
                            notyf.error('Seuls les formats JPEG, JPG ou PNG sont acceptés pour l\'image.');
                            return;
                        }
                        selectedFile.value = file;
                        previewAvatar.value = URL.createObjectURL(file);
                    }
                };

                const handleSave = async () => {
                    const data = new FormData();
                    data.append('nom', formData.value.nom);
                    data.append('prenom', formData.value.prenom);
                    data.append('email', formData.value.email);
                    data.append('phone', formData.value.phone);
                    data.append('location', formData.value.location);
                    data.append('date_de_naissance', formData.value.date_de_naissance);
                    data.append('diplomes', formData.value.diplomes);
                    data.append('experiences', formData.value.experiences);
                    if (selectedFile.value) {
                        data.append('photo', selectedFile.value);
                    }

                    try {
                        const response = await fetch('/enqueteur/profile', {
                            method: 'POST',
                            body: data,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                        });
                        if (response.ok) {
                            const updatedEnqueteur = await response.json();
                            enqueteur.value = updatedEnqueteur;
                            previewAvatar.value = updatedEnqueteur.photo ? updatedEnqueteur.photo_url :
                                null;
                            isEditing.value = false;
                            selectedFile.value = null;
                            notyf.success('Profil mis à jour avec succès !');
                        } else {
                            notyf.error('Échec de la mise à jour du profil.');
                        }
                    } catch (error) {
                        notyf.error('Une erreur est survenue : ' + error.message);
                    }
                };

                const memberSince = computed(() => {
                    const date = new Date(enqueteur.value.created_at);
                    return 'Membre depuis ' + date.toLocaleString('fr-FR', {
                        month: 'long',
                        year: 'numeric'
                    });
                });

                return {
                    enqueteur,
                    isEditing,
                    formData,
                    previewAvatar,
                    handleInputChange,
                    triggerFileInput,
                    handleFileChange,
                    handleSave,
                    memberSince,
                };
            }
        });

        app.mount('#profile-app');
    </script>
@endsection
