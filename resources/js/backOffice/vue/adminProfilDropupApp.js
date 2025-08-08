import { createApp } from 'vue';
import AdminProfilDropup from './components/AdminProfilDropup.vue';

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function () {
    const adminProfilElement = document.getElementById('adminProfilDropupApp');

    if (adminProfilElement) {
        const adminApp = createApp({});
        adminApp.component('admin-profil-dropup', AdminProfilDropup);
        adminApp.mount('#adminProfilDropupApp');
    }
});
