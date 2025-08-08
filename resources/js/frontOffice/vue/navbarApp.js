import { createApp } from 'vue';
import ProfileDropdown from './components/ProfileDropdown.vue';


const navbarApp = createApp({});
navbarApp.component('profile-dropdown', ProfileDropdown);
navbarApp.mount('#navbarApp');
