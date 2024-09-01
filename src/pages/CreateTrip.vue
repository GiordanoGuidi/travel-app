<script>
import CreateTripForm from '../components/forms/CreateTripForm.vue';
import { store } from '../data/store';
const endpoint = 'https://prao-app.42web.io/travel-app-back';
export default {
    name: 'CreateTrip',
    data: () => ({
        store
    }),
    components: { CreateTripForm },
    methods: {
        //Metodo per creare un viaggio
        createTrip(tripData) {
            // Setto la flag del loder a true
            store.isLoading = true;
            fetch(`${endpoint}/create_trip.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                //converto l'oggetto tripData in una stringa JSON
                body: JSON.stringify(tripData)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Errore nella richiesta al server');
                    }
                    //Trasformo la stringa json in un oggetto js
                    return response.json();
                })
                .then(data => {
                    console.log('Dati ricevuti dal backend:', data); // Logga la risposta completa
                    if (data.status === 'success') {
                        //reindirizzo alla pagina del dettaglio del viaggio
                        this.$router.push({ name: 'trip-details', params: { id: data.trip_id } });
                    } else {
                        // Logga il messaggio di errore ricevuto dal backend (se presente)
                        console.error('Errore dal backend:', data.message || 'Nessun messaggio di errore fornito');
                        alert('Errore nella creazione del viaggio.');
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
    }
}
</script>

<template>
    <!-- Sezione create-trip -->
    <section id="create-trip" class="d-flex justify-content-center align-items-center h-100 flex-column">
        <h1 class="mb-4">Creao Il tuo viaggio</h1>
        <!-- Form di creazione del viaggio -->
        <CreateTripForm @submit-trip="createTrip" />
    </section>
</template>