<script>
import TripCard from '../components/TripCard.vue';
const endpoint = 'http://localhost:8888/boolean/travel-app-back';
export default {
    name: 'TripDetails',
    components: { TripCard },
    data: () => ({
        trip: null,
    }),
    methods: {
        //Metodo per recuperare i dati del viaggio
        getTrip() {
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
        },
        //Metodo per inviare il form per aggiungere una tappa
        submitForm(stop, dayIndex) {
            fetch(`${endpoint}/add_stop.php?id=${this.$route.params.id}&day=${dayIndex}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                //converto l'oggetto stop in una stringa JSON
                body: JSON.stringify(stop, dayIndex)
            })
                .then(response => {
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
                        alert('Errore nella creazione del viaggio.');
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    alert('Si è verificato un errore');
                });
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
            <TripCard :trip="this.trip" @trigger-submit-form="submitForm" />
        </div>
    </section>
</template>

<style scoped lang="scss"></style>