<script>
import { store } from '../data/store';
//Endpoint in deploy 
// const endpoint = 'https://4bc609ae-1fbd-4e17-b4e1-873fad957ede-00-1mbb2cxqxrq2h.kirk.replit.dev';
//endpoint locale
const endpoint = "http://localhost:8888/boolean/travel-app/backend";


export default {
    name: 'TripList',
    data: () => ({
        store,
        trips: null,
    }),
    methods: {
        //Funzione per recuperare tutti i viaggi
        getTrips() {
            store.isLoading = true;
            fetch(`${endpoint}/get_trips.php`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    console.log(response)
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    if (data.status == 'success') {
                        this.trips = data.trips;
                    } else {
                        this.error = data.message;
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    alert('Si è verificato un errore');
                })
                .then(() => {
                    store.isLoading = false;
                })
        }
    },
    created() {
        //Invoca la funzione alla creazione
        this.getTrips();
    }
}
</script>

<template>
    <section id="trip-list">
        <div class="row gap-5 justify-content-center mt-5">
            <div class="trips-container  w-75">
                <ul class="list-unstyled d-flex gap-4 flex-wrap row">
                    <!-- Itero sull'array di viaggi -->
                    <div v-for="trip in this.trips" class="trip-card col-4 p-2">
                        <!-- Al click eseguo il redirect sul dettaglio del viaggio -->
                        <RouterLink class="text-decoration-none text-dark h-100 w-100"
                            :to="{ name: 'trip-details', params: { id: trip.id } }">
                            <li class="h-100 w-100">
                                <h1 class="text-center">{{ trip.destination }}</h1>
                                <p class="text-center text-size">{{ trip.start_date }} / {{ trip.end_date }}</p>
                            </li>
                        </RouterLink>
                    </div>
                    <!-- Template visualizzato se non ci sono viaggi -->
                    <div v-if="!this.trips" class="d-flex justify-content-center">
                        <div class="w-75 text-center empty-trips p-2">
                            <h1>Non ci sono ancora viaggi</h1>
                            <!-- Al click sul bottone eseguo il redirect alla pagina di creazione del viaggio -->
                            <RouterLink class="text-decoration-none text-dark" :to="{ name: 'create-trip' }">
                                <button class="btn btn-primary mt-4">Crea subito il tuo primo viaggio</button>
                            </RouterLink>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </section>
</template>

<style scoped lang="scss">
.trip-card {
    height: 200px;
    width: 300px;
    background-color: rgba(220, 220, 220, 0.7);
    border-radius: 10px;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.25);

    &:hover {
        background-color: rgba(100, 100, 100, 0.7);
        transition: 1s;
    }

    .text-size {
        font-size: 0.9rem;
    }
}

.empty-trips {
    background-color: rgba(220, 220, 220, 0.7);
    border-radius: 10px;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.25);
    height: 200px;
}
</style>
