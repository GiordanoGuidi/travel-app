<script>
import AddStopForm from '../components/forms/AddStopForm.vue'
const endpoint = 'http://localhost:8888/boolean/travel-app-back';
export default {
    name: 'TripDetails',
    components: { AddStopForm },
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
                        //Assegno un numero = di tappe alla durata del viaggio
                        for (let i = 0; i < this.trip.duration; i++) {
                            //Aggiungo una tappa vuota per non lasciare l'array vuoto
                            this.trip.days.push({ stops: [{ title: '', description: '' }] });
                        }
                    } else {
                        this.error = data.message;
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    alert('Si è verificato un errore');
                })
        },
        addStop(day) {
            this.trip.days[day].stops.push({ title: '', description: '' })
        },
        removeStop(dayIndex, stopIndex) {
            this.trip.days[dayIndex].stops.splice(stopIndex, 1);
        },
        //Metodo per inviare il form per aggiungere una tappa
        submitForm(stop, dayIndex) {
            console.log(stop, dayIndex);
            console.log(this.$route.params.id);
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
                        console.log(data);
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
    <h1 class="text-center mt-5">TripDetails</h1>
    <div v-if="trip">
        <p>Località : {{ trip.destination }}</p>
        <p>Durata del viaggo :{{ trip.duration }}</p>
        <p>Data inizio viaggio : {{ trip.start_date }}</p>
        <p>Data di fine viaggio : {{ trip.end_date }}</p>
        <p>Tappe : {{ trip.days.stops }}</p>
        <p>{{ console.log(trip) }}</p>
        <div v-for="(day, dayIndex) in trip.days" :key="dayIndex">
            <h3>Giornata: {{ dayIndex + 1 }}</h3>
            <div v-for="(stop, stopIndex) in day.stops" :key="stopIndex">
                <AddStopForm :stop="stop" @remove-stop="removeStop(dayIndex, stopIndex)"
                    @submit-form="submitForm(stop, dayIndex)" />
            </div>
            <!--Bottone per aggiungere una tappa-->
            <button class="mt-3" @click="addStop(dayIndex)">Aggiungi tappa</button>
        </div>

    </div>
</template>