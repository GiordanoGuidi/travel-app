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
    }),
    methods: {
        //Metodo per aggiungere una tappa vuota 
        addStop(dayIndex) {
            this.formActive = true;
            this.currentDayIndex = dayIndex;
            this.newStop = { title: '', description: '', image: 'placeholder.jpg' };
        },
        //Metodo per chiudere il form di aggiunta della tappa
        closeForm() {
            this.formActive = false;
        },
        //Metodo che invoca la funzione per il submit del form
        triggerSubmitForm(stop, dayIndex) {
            this.$emit('trigger-submit-form', stop, dayIndex);
            // Ritardo la disattivazione del form
            setTimeout(() => {
                this.closeForm();
            }, 50);
        }
    }
}
</script>

<template>
    <div class="trip-card d-flex flex-column align-items-center p-4">
        <!-- Destinazione -->
        <h1 class="mb-3">{{ trip.destination.toUpperCase() }}</h1>
        <!-- Partenza -->
        <p>Partenza : ({{ trip.start_date }})</p>
        <!-- Rientro -->
        <p class="m-0">Rientro : ({{ trip.end_date }})</p>
        <!-- Itero sul numero delle giornate del viaggio -->
        <div v-for="(day, dayIndex) in trip.days" :key="dayIndex">
            <!-- Giorno del viaggio -->
            <h3 class="mt-3">Giorno : {{ dayIndex + 1 }}</h3>
            <!-- Recupero l'array di tappe della giornata -->
            <div v-for="(stop, stopIndex) in day.stops" :key="stopIndex">
                <ul>
                    <RouterLink :to="{ name: 'stop-details' }">
                        <li class="stop-card d-flex gap-5">
                            <p>Tappa: {{ stop.title }}</p>
                            <p>Descrizione: {{ stop.description }}</p>
                            <p>Id: {{ stop.id }}</p>
                            <p>image {{ stop.image }}</p>
                            <p>{{ day.stops }}</p>
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
        background-color: aqua;
    }
}
</style>