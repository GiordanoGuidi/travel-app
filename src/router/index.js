//Importo le funzioni di Vue Router
import { createRouter, createWebHistory } from 'vue-router';
//Importo le pagine
import TripDetails from '../pages/TripDetails.vue';
import CreateTrip from '../pages/CreateTrip.vue';
import HomePage from '../pages/HomePage.vue';

const router = createRouter({
    history: createWebHistory(),

    linkActiveClass: 'partial-active',
    linkExactActiveClass: 'active',
    routes: [
        //Rotta per l'Homepage
        { path: '/', name: 'home-page', component: HomePage },
        //Rotta per la creazione del viaggio
        { path: '/create-trip', name: 'create-trip', component: CreateTrip },
        //Rotta per il dettaglio del viaggio
        { path: '/trip/:id', name: 'trip-details', component: TripDetails },
    ]
});
export { router };