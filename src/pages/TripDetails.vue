<script>
const endpoint = 'http://localhost:8888/boolean/travel-app-back';
export default {
    name: 'TripDetails',
    data: () => ({
        trip: null,
    }),
    methods: {
        //Metodo per recuperare i dati del viaggio
        getTrip() {
            fetch(`${endpoint}/get_trip.php/?id=${this.$route.params.id}`, {
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
        }
    },
    created() {
        //Invoco la funzione alla creazione
        this.getTrip();
    }

}
</script>

<template>
    <h1 class="text-center mt-5">TripDetails</h1>
    <p>Località : {{ trip.destination }}</p>
    <p>Durata del viaggo :{{ trip.duration }}</p>
    <p>Data inizio viaggio : {{ trip.start_date }}</p>
    <p>Data di fine viaggio : {{ trip.end_date }}</p>
    <p>Tappe : {{ trip.days }}</p>
</template>