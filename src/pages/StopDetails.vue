<script>
const endpoint = 'https://prao-app.42web.io/travel-app-back';
// const endpoint = "http://localhost:8888/boolean/travel-app-back";

import { store } from '../data/store';

export default {
    name: 'StopDetails',
    data: () => ({
        stop: null,
        store
    }),
    methods: {
        //Metodo per recuperare la tappa
        getStop() {
            fetch(`${endpoint}/get_stop.php?id=${this.$route.params.id}&stop_id=${this.$route.params.stop_id}&stop_day=${this.$route.params.stop_day}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.status == 'success') {
                        this.stop = data.stop;
                        // Eseguo il geocoding dell'indirizzo della tappa
                        this.geocodeAddress(this.stop.address);
                    } else {
                        this.error = data.message
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    alert('Si è verificato un errore');
                })
        },
        //Funzione per recuperare la posizione dell'indirizzo della tappa
        geocodeAddress(address) {
            store.isLoading = true
            //Api key
            const apiKey = 'LXqcovy5Bx4NKyO2Xe0eXclcet8eiE6s';
            fetch(`https://api.tomtom.com/search/2/geocode/${encodeURIComponent(address)}.json?key=${apiKey}`)
                .then(response => response.json())
                .then(data => {
                    if (data.results && data.results.length > 0) {
                        //Recupero la posizione
                        const position = data.results[0].position;
                        //Recupero la latitudine
                        const latitude = position.lat;
                        //Recupero la longitudine
                        const longitude = position.lon;
                        // Visualizza la mappa con le coordinate ottenute
                        this.displayMap(latitude, longitude);
                    } else {
                        console.error('Nessuna posizione trovata per l\'indirizzo fornito.');
                    }
                })
                .catch(error => console.error('Errore nel geocoding:', error))
                .then(() => {
                    store.isLoading = false;
                })
        },
        displayMap(latitude, longitude) {
            const map = tt.map({
                key: 'LXqcovy5Bx4NKyO2Xe0eXclcet8eiE6s',
                //Recupero l'elemento html con id map
                container: 'map',
                center: [longitude, latitude],
                //Setto lo zoom della visualizzazione della localizzazione
                zoom: 15
            });
            //Creo il marcatore
            const marker = new tt.Marker()
                //imposto la posizione del marcatore
                .setLngLat([longitude, latitude])
                //Lo aggiungo alla variabile mappa
                .addTo(map);
        }

    },
    created() {
        //Invoco la funzione alla creazione
        this.getStop();
    }
}
</script>
<template>
    <section id="stop-detail">
        <div v-if="this.stop" class="stop-details-card row">
            <div class="row title-and-image">
                <!-- Titolo della tappa -->
                <div class="title-container col-6">
                    <h1>{{ this.stop.title }}</h1>
                </div>
                <!-- Immagine della tappa -->
                <div class="img-container col-6">
                    <img :src="this.stop.image" alt="#">
                </div>
            </div>
            <!-- Descrizione della tappa -->
            <div class="description-container col-8">
                <p>{{ this.stop.description }}</p>
            </div>
            <!-- Contenitore della mappa -->
            <div id="map" class="map-container"></div>
            <!-- Redirect per il dettaglio del viaggio -->
            <div class="text-center mt-3">
                <RouterLink class="btn btn-primary"
                    :to="{ name: 'trip-details', params: { id: this.$route.params.id } }">
                    Indietro
                </RouterLink>
            </div>
        </div>

    </section>
</template>

<style scoped lang="scss">
#stop-detail {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}


.stop-details-card {
    height: 80%;
    width: 75%;
    overflow-y: auto;
    background-color: rgba(150, 150, 150, 0.7);
    border-radius: 10px;
    padding: 50px;
    margin: 0 auto;
}

.title-and-image {
    justify-content: space-between;
    width: 75%;
    margin: 0 auto;
}

.title-container {
    width: 100px;
    height: 100px;
}

.img-container {
    width: 200px;
    height: 150px;

    img {
        width: 100%;
        height: auto;
    }
}

.description-container {
    padding-bottom: 30px;
    width: 75%;
    margin: 0 auto;
}

.map-container {
    height: 200px;
    width: 75%;
    padding: 50px;
    margin: 0 auto;
    padding-bottom: 50px;
}
</style>