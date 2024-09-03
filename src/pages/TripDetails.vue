<script>
import TripCard from '../components/TripCard.vue';
import { store } from '../data/store';
//Endpoint in deploy 
// const endpoint = 'https://4bc609ae-1fbd-4e17-b4e1-873fad957ede-00-1mbb2cxqxrq2h.kirk.replit.dev';
//endpoint locale
const endpoint = "http://localhost:8888/boolean/travel-app/backend";



export default {
    name: 'TripDetails',
    components: { TripCard },
    data: () => ({
        trip: null,
        store
    }),
    methods: {
        //Metodo per recuperare i dati del viaggio
        getTrip() {
            store.isLoading = true;
            fetch(`${endpoint}/get_trip.php?id=${this.$route.params.id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
            })
                .then(response => {
                    //Trasformo la stringa json in un oggetto js
                    return response.json();
                })
                .then(data => {
                    if (data.status == 'success') {
                        //salvo il contenuto di data nella variabile
                        this.trip = data.trip;
                    } else {
                        this.error = data.message;
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    alert('Si è verificato un errore');
                })
                .then(() => {
                    // Setto la flag del loder a false
                    store.isLoading = false;
                })
        },
        //Metodo per inviare il form per aggiungere una tappa
        submitForm(stop, dayIndex) {
            store.isLoading = true;
            fetch(`${endpoint}/add_stop.php?id=${this.$route.params.id}&day=${dayIndex}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                //converto l'oggetto stop in una stringa JSON
                body: JSON.stringify(stop, dayIndex)
            })
                .then(response => {
                    console.log(response);
                    if (!response.ok) {
                        throw new Error('Errore nella richiesta al server');
                    }
                    // Trasformo la stringa json in un oggetto js
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        //Aggiorno il viaggio con la nuova tappa
                        this.trip = data.trip
                    } else {
                        alert('Errore nella creazione della tappa.');
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    alert('Si è verificato un errore');
                })
                .then(() => {
                    // Setto la flag del loder a false
                    store.isLoading = false;
                })
        },
        //Metodo per la rimozione di una tappa da un viaggio
        removeStop(tripId, stopId, dayIndex) {
            store.isLoading = true;
            fetch(`${endpoint}/delete_stop.php`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                //converto l'oggetto stop in una stringa JSON
                body: JSON.stringify({
                    tripId: tripId,
                    stopId: stopId,
                    dayIndex: dayIndex,
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Errore nella richiesta al server');
                    }
                    // Trasformo la stringa json in un oggetto js
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    if (data.status === 'success') {
                        //Aggiorno il viaggio con la nuova tappa
                        this.trip = data.trip
                    } else {
                        alert('Errore nella rimozione della tappa.');
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    alert('Si è verificato un errore');
                })
                .then(() => {
                    // Setto la flag del loder a false
                    store.isLoading = false;
                })
        },
        //Metodo per modificare lo status di una tappa
        updateStopStatus(tripId, dayIndex, stop) {
            store.isLoading = true;
            fetch(`${endpoint}/update_stop_status.php`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                //converto l'oggetto stop in una stringa JSON
                body: JSON.stringify({
                    tripId: tripId,
                    stopId: stop.id,
                    dayIndex: dayIndex,
                    status: stop.status,
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Errore nella richiesta al server');
                    }
                    // Trasformo la stringa json in un oggetto js
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    if (data.status === 'success') {
                        //Aggiorno il viaggio 
                        this.trip = data.trip
                    } else {
                        alert('Errore nella modifica dello status.');
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    alert('Si è verificato un errore');
                    // Ripristino lo stato in caso di errore
                    stop.status = !stop.status
                })
                .then(() => {
                    // Setto la flag del loder a false
                    store.isLoading = false;
                })
        }
    },
    created() {
        //Invoco la funzione alla creazione
        this.getTrip();
    }
}
</script>

<template>
    <!-- Dettaglio del viaggio -->
    <section id="trip-details" class="h-100">
        <div v-if="trip" class="d-flex justify-content-center align-items-center h-100 pb-3 pt-3">
            <!-- Card del viaggio -->
            <TripCard :trip="this.trip" @trigger-submit-form="submitForm" @trigger-remove-stop="removeStop"
                @trigger-update-stop-status="updateStopStatus" />
        </div>
    </section>
</template>

<style scoped lang="scss"></style>