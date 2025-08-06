import { createApp } from 'vue';
import ModalFormOffer from './components/ModalFormOffer.vue';


const modal = createApp({});
modal.component('modal-form-offer', ModalFormOffer);
modal.mount('#modalFormOfferApp');
