//Importo le funzioni di Vue Router
import { createRouter, createWebHistory } from 'vue-router';
//Importo le pagine
import TripDetails from '../pages/TripDetails.vue';

const router = createRouter({
    history: createWebHistory(),

    linkActiveClass: 'partial-active',
    linkExactActiveClass: 'active',
    routes: [
        { path: '/trip/:id', name: 'trip-details', component: TripDetails },

    ]
});
export { router };