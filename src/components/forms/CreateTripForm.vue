<script>
import { store } from '../../data/store';

export default {
    name: 'AppForm',
    data: () => ({
        trip: store.trip,
    }),
    methods: {
        submitForm() {
            // Emetto un custom event all'invio del form
            this.$emit('submit-trip', this.trip);
        }
    }
}
</script>

<template>
    <div class="form-container d-flex align-items-center justify-content-center">
        <!-- Form per creare un nuovo viaggio -->
        <form @submit.prevent="submitForm">
            <div class="d-flex gap-2 flex-wrap">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <!-- Destinazione -->
                    <label for="destination" class="form-label">Destinazione: </label>
                    <input class="rounded p-1" id="destination" type="text" v-model="trip.destination"
                        placeholder="Meta del viaggio">
                </div>
                <!-- Data di inizio -->
                <div class="d-flex align-items-center gap-2 mb-2">
                    <label for="start" class="form-label">Partenza: </label>
                    <input class="rounded p-1" id="start" type="date" v-model="trip.start_date">
                </div>
                <!-- Data di fine -->
                <div class="d-flex align-items-center gap-2 mb-2">
                    <label for="end" class="form-label">Rientro: </label>
                    <input class="rounded p-1" id="end" type="date" v-model="trip.end_date">
                </div>

            </div>
            <div class="d-flex align-items-center justify-content-center mt-4">
                <button class="rounded p-1" type="submit">Crea Viaggio</button>
            </div>
        </form>
    </div>
</template>

<style scoped lang="scss">
.form-container {
    height: 200px;
    width: 1000px;
    background-color: rgba(200, 200, 200, 0.5);
    border-radius: 10px;

    form {
        height: 40px;
    }
}
</style>