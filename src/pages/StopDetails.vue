<script>
const endpoint = 'http://localhost:8888/boolean/travel-app-back';
export default {
    name: 'StopDetails',
    data: () => ({
        stop: null,
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
                    } else {
                        this.error = data.message
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
        this.getStop();
    }
}
</script>
<template>
    <section id="stop-details" class="d-flex justify-content-center h-100 p-4">
        <div class="stop-details-card d-flex flex-column align-items-center w-75 p-5 row">
            <div class="y d-flex w-75 justify-content-between">
                <h1 class="text-white col-6">{{ this.stop.title }}</h1>
                <div class="img-container col-6">
                    <img class="img-fluid" :src="this.stop.image" alt="">
                </div>
            </div>
            <div class="w-75 mt-5 col-12">
                <p>{{ this.stop.description }}</p>
            </div>
        </div>
        <p class="text-white">{{ this.stop.address }}</p>
    </section>

</template>

<style>
.stop-details-card {
    width: 1000px;
    height: 100%;
    max-height: 100%;
    background-color: rgba(150, 150, 150, 0.7);
    /* background-color: red; */
    border-radius: 10px;
    overflow-y: scroll;

    img {
        object-fit: fill;
        width: 100%;
        height: 100%;
    }

    .img-container {
        height: 150px;
        width: 300px;
    }
}
</style>