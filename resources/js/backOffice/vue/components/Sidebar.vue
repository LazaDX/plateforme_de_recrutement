<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { route } from 'ziggy-js';  // <- Importer route ici
import { Ziggy } from '../../../ziggy';  //

const props = defineProps({
    logoUrl: String,
    adminName: String,
    adminAvatar: String,
    logoutUrl: String,
    csrfToken: String,
    currentRoute: String,
    open: Boolean, // Ajouter open comme prop
});

const emit = defineEmits(['update:open']); // Émettre un événement pour mettre à jour la prop open

const windowWidth = ref(1024);
const navItems = ref([]);
const sidebar = ref(null);
const isMounted = ref(false);

const urlDashboard = route('admin.dashboard', undefined, undefined, Ziggy);
const urlOffers = route('admin.offers', undefined, undefined, Ziggy);
const urlEnqueteurs = route('admin.enqueteurs', undefined, undefined, Ziggy);
const urlAnalytiques = route('admin.analytiques', undefined, undefined, Ziggy);
const urlAdministrateurs = route('admin.administrateurs', undefined, undefined, Ziggy);

const isMobile = computed(() => isMounted.value && windowWidth.value < 1024);

const isActive = (route) => props.currentRoute === route;

const handleResize = () => {
    if (typeof window !== 'undefined') {
        windowWidth.value = window.innerWidth;
        emit('update:open', windowWidth.value >= 1024); // Mettre à jour le parent
    }
};

const handleClickOutside = (event) => {
    if (windowWidth.value < 1024 && sidebar.value && !sidebar.value.contains(event.target)) {
        emit('update:open', false);
    }
};

const handleClickInside = (event) => event.stopPropagation();

onMounted(() => {
    isMounted.value = true;
    handleResize();

    if (typeof window !== 'undefined') {
        navItems.value = [
            { label: 'Tableau de bord', route: 'admin.dashboard', icon: 'fas fa-tachometer-alt', href: urlDashboard },
            { label: 'Offres d\'enquêtes', route: 'admin.offers', icon: 'fas fa-briefcase', href: urlOffers },
            { label: 'Enquêteurs', route: 'admin.enqueteurs', icon: 'fas fa-users', href: urlEnqueteurs },
            { label: 'Analytiques', route: 'admin.analytiques', icon: 'fas fa-chart-bar', href: urlAnalytiques },
            { label: 'Administrateurs', route: 'admin.administrateurs', icon: 'fas fa-user', href: urlAdministrateurs },
        ];
        window.addEventListener('resize', handleResize);
        document.addEventListener('click', handleClickOutside);
    }
});

onUnmounted(() => {
    if (typeof window !== 'undefined') {
        window.removeEventListener('resize', handleResize);
        document.removeEventListener('click', handleClickOutside);
    }
});
</script>

<template>
    <div v-if="isMounted">
        <!-- Fond mobile -->
        <div
            v-if="isMobile"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
            v-show="props.open"
            @click="emit('update:open', false)"
        ></div>

        <!-- Barre latérale -->
        <div
            ref="sidebar"
            v-show="props.open || !isMobile"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r rounded-md border-gray-200 transform flex flex-col"
            :class="{ 'translate-x-0': props.open, '-translate-x-full': !props.open }"
            @click="handleClickInside"
        >
            <div class="flex-1 overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <div>
                        <img :src="logoUrl" class="h-14 px-4" alt="Logo">
                    </div>
                    <button @click="emit('update:open', false)" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-times h-5 w-5 text-gray-600"></i>
                    </button>
                </div>
                <nav class="p-4 mt-4 space-y-2">
                    <a
                        v-for="item in navItems"
                        :key="item.route"
                        :href="item.href"
                        class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors duration-200"
                        :class="{
                            'bg-gray-900 text-white': isActive(item.route),
                            'text-gray-700 hover:bg-gray-100': !isActive(item.route),
                        }"
                    >
                        <i :class="item.icon + ' mr-3 h-5 w-5'"></i>
                        {{ item.label }}
                    </a>
                </nav>
            </div>
            <!-- Profil Admin -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <img
                            class="w-10 h-10 rounded-full object-cover"
                            :src="adminAvatar"
                            alt="Admin Avatar"
                        >
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ adminName }}</p>
                        <p class="text-xs text-gray-500">Administrateur</p>
                    </div>
                </div>
                <div class="mt-8 pl-3">
                    <form :action="logoutUrl" method="POST">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <button type="submit" class="text-xs text-gray-500 hover:text-gray-700 flex items-center">
                            <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
