import { createApp } from 'vue';
import ProfileDropdown from './components/ProfileDropdown.vue';


const navbarApp = createApp({});
navbarApp.component('ProfileDropdown', ProfileDropdown);
navbarApp.mount('#navbarApp');
