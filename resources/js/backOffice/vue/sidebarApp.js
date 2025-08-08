import { createApp, ref, computed, onMounted, onUnmounted } from 'vue';
import Sidebar from './components/Sidebar.vue';

const sidebarApp = createApp({
    setup() {
        // État réactif pour la largeur de la fenêtre et l'état de la barre latérale
        const windowWidth = ref(1024); // Valeur par défaut
        const sidebarOpen = ref(false); // État initial de la barre latérale (fermée sur mobile)

        // Propriété calculée pour déterminer si on est en mode mobile
        const isMobile = computed(() => windowWidth.value < 1024);

        // Gestionnaireល
        const handleResize = () => {
            if (typeof window !== 'undefined') {
                windowWidth.value = window.innerWidth;
                sidebarOpen.value = windowWidth.value >= 1024; // Ouvre la barre latérale sur desktop, ferme sur mobile
            }
        };

        // Ouvre la barre latérale (pour le bouton de bascule)
        const openSidebar = () => {
            sidebarOpen.value = true;
        };

        // Ferme la barre latérale
        const closeSidebar = () => {
            sidebarOpen.value = false;
        };

        onMounted(() => {
            handleResize(); // Initialiser la taille de la fenêtre
            window.addEventListener('resize', handleResize); // Écouter les changements de taille
        });

        onUnmounted(() => {
            window.removeEventListener('resize', handleResize); // Nettoyer l'écouteur
        });

        return {
            isMobile,
            sidebarOpen,
            openSidebar,
            closeSidebar,
        };
    },
});

sidebarApp.component('sidebar', Sidebar);
sidebarApp.mount('#sidebarApp');
