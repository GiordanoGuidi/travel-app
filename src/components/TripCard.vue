<script>
import AddStopForm from '../components/forms/AddStopForm.vue';
export default {
    name: 'TripCard',
    components: { AddStopForm },
    props: { trip: Object },
    data: () => ({
        formActive: false,
        currentDayIndex: null,
        newStop: null,
        fileImage: null,
    }),
    methods: {
        //Metodo per aggiungere una tappa vuota 
        addStop(dayIndex) {
            this.formActive = true;
            this.currentDayIndex = dayIndex;
            this.newStop = { title: '', description: '', image: '../../../public/placeholder.jpg', address: '', status: 'undone' };
        },
        //Metodo per chiudere il form di aggiunta della tappa
        closeForm() {
            this.formActive = false;
        },
        //Metodo che invoca la funzione per il submit del form
        triggerSubmitForm(stop, dayIndex, imagePreview) {
            this.fileImage = imagePreview;
            this.$emit('trigger-submit-form', stop, dayIndex);
            // Ritardo la disattivazione del form
            setTimeout(() => {
                this.closeForm();
            }, 100);
        },
    }
}
</script>

<template>
    <div class="trip-card d-flex flex-column align-items-center p-4">
        <!-- Destinazione -->
        <h1 class="mb-3">{{ trip.destination.toUpperCase() }}</h1>
        <!-- Partenza -->
        <p> {{ trip.start_date }} / {{ trip.end_date }}</p>
        <!-- Itero sul numero delle giornate del viaggio -->
        <div v-for="(day, dayIndex) in trip.days" :key="dayIndex" class="w-75">
            <!-- Giorno del viaggio -->
            <h3 class="mt-3">Giorno : {{ dayIndex + 1 }}</h3>
            <!-- Recupero l'array di tappe della giornata -->
            <div v-for="(stop, stopIndex) in day.stops" :key="stopIndex">
                <ul class="list-unstyled">
                    <RouterLink v-if="this.trip" class="text-decoration-none text-dark"
                        :to="{ name: 'stop-details', params: { id: trip.id, stop_id: stop.id, stop_day: dayIndex } }">
                        <li class="stop-card d-flex justify-content-between align-items-center mb-5 w-50">
                            <p>{{ stop.title }}</p>
                            <div class="img-container d-flex align-items-center">
                                <img :src="stop.image" alt="">
                            </div>
                            <a href="">ciaoo</a>
                        </li>
                    </RouterLink>
                </ul>
            </div>
            <div v-if="formActive && currentDayIndex === dayIndex">
                <!-- Form -->
                <AddStopForm :stop="newStop" :day="dayIndex" @close-form="closeForm" @submit-form="triggerSubmitForm" />
            </div>
            <!--Bottone per aggiungere una tappa-->
            <button class="mt-3 btn btn-primary" @click="addStop(dayIndex)">Aggiungi tappa</button>
        </div>
    </div>
</template>

<style scoped lang="scss">
.trip-card {
    width: 1000px;
    max-height: 100%;
    background-color: rgba(220, 220, 220, 0.7);
    border-radius: 10px;
    overflow-y: scroll;

    .stop-card {
        cursor: pointer;
        padding: 10px;
        background-color: rgba(220, 220, 220, 0.7);
        border-radius: 10px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.25);


        &:hover {
            background-color: rgba(100, 100, 100, 0.7);
            transition: 1s;
        }
    }

    .img-container {
        width: 100px;
        height: 100px;

        img {
            max-width: 100%;
            max-height: 100%;
        }
    }
}
</style>