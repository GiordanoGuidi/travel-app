<script>
import { store } from '../../data/store';

export default {
    name: 'AppForm',
    data: () => ({
        trip: store.trip,
    }),
    methods: {
        submitForm() {
            console.log('Form inviato', this.trip); // Debug
            // Emetto un custom event all'invio del form
            this.$emit('submit-trip', this.trip);
        }
    }
}
</script>

<template>
    <!-- Form per creare un nuovo viaggio -->
    <form @submit.prevent="submitForm">
        <input type="text" v-model="trip.destination" placeholder="Meta del viaggio" required>
        <input type="number" v-model="trip.duration" placeholder="Durata in giorni" required>
        <input type="date" v-model="trip.start_date" placeholder="Data di inizio" required>
        <button type="submit">Crea Viaggio</button>
    </form>
</template>