<script>
import AddStopForm from '../components/forms/AddStopForm.vue';
import StopCard from '../components/StopCard.vue';
export default {
    name: 'TripCard',
    components: { AddStopForm, StopCard },
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
            this.newStop = { title: '', description: '', image: '../../../public/placeholder.jpg', address: '', status: false };
        },
        //Metodo che avvisa della rimozione di una tappa
        triggerRemoveStop(tripId, dayIndex, stop) {
            this.$emit('trigger-remove-stop', tripId, stop.id, dayIndex)
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
        //Metodo che invoca la funzione per aggiornare lo status di una tappa
        updateStatus(tripId, dayIndex, stop) {
            this.$emit('trigger-update-stop-status', tripId, dayIndex, stop)
        }
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
            <ul class="list-unstyled h-100 d-flex flex-column gap-4">
                <div v-for="(stop, stopIndex) in day.stops" :key="stopIndex" class="d-flex gap-5">
                    <!-- Al click sulla card eseguo il redirect alla pagina di dettaglio della tappa -->
                    <RouterLink v-if="this.trip"
                        class="text-decoration-none text-dark d-flex align-items-center gap-5 col-8"
                        :to="{ name: 'stop-details', params: { id: trip.id, stop_id: stop.id, stop_day: dayIndex } }">
                        <!-- Card della tappa -->
                        <StopCard :stop="stop" />
                    </RouterLink>
                    <div class="d-flex justify-content-center align-items-center gap-5">
                        <!-- Bottone per eliminare la tappa -->
                        <button class="delete-button" @click="triggerRemoveStop(trip.id, dayIndex, stop)" href="#">
                            <font-awesome-icon class="text-danger pulse-icon" :icon="['fas', 'fa-trash-can']" />
                        </button>
                        <!-- Switcher per segnare la tappa come completata -->
                        <div class="form-check form-switch">
                            <input v-model="stop.status" @change="updateStatus(trip.id, dayIndex, stop)"
                                class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        </div>
                    </div>
                </div>
            </ul>
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
@use '@assets/scss/generics.scss';

.trip-card {
    width: 1000px;
    max-height: 100%;
    background-color: rgba(220, 220, 220, 0.7);
    border-radius: 10px;
    overflow-y: scroll;
}
</style>