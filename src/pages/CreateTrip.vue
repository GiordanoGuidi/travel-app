<script>
import AppForm from '../components/forms/AppForm.vue';
export default {
    name: 'CreateTrip',
    components: { AppForm },
    methods: {
        //Metodo per creare un viaggio
        createTrip(tripData) {
            fetch('http://localhost:8888/boolean/travel-app-back/create_trip.php', {
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
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        //reindirizzo alla pagina del dettaglio del viaggio
                        this.$router.push({ name: 'trip-details', params: { id: data.trip_id } });
                    } else {
                        alert('Errore nella creazione del viaggio.');
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    alert('Si è verificato un errore. Controlla la console per maggiori dettagli.');
                });
        }
    }
}
</script>

<template>
    <AppForm @submit-trip="createTrip" />
</template>