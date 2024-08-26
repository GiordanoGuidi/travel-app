<script>
import { store } from '../../data/store';
const regex = /^[a-zA-Z\s]+$/;

export default {
    name: 'AppForm',
    data: () => ({
        trip: store.trip,
        errors: {},
    }),
    methods: {
        //Metodo eseguito al submit del form
        submitForm() {
            //Invoco funzione per validazione del form
            if (this.validateForm(this.trip)) {
                // Emetto un custom event se il form non presenta errori
                this.$emit('submit-trip', this.trip);
            }
        },
        //Funzione per validare i campi del form di creazione del viaggio
        validateForm(trip) {
            const { destination, start_date, end_date } = trip;
            console.log(start_date);
            this.errors = {};
            if (!destination) {
                this.errors.destination = 'La destinazione è obbligatoria'
            } else if (!isNaN(destination)) {
                this.errors.destination = 'La destinazione non può essere un numero'
            } else if (!regex.test(destination)) {
                this.errors.destination = 'La destinazione non può contenere numeri e caratteri speciali'
            }
            if (!start_date) {
                this.errors.start_date = 'La data di partenza è obbligatoria'
            } else if (start_date < this.getCurrentDate()) {
                this.errors.start_date = 'La data di partenza è passata'
            } else if (start_date > end_date) {
                this.errors.start_date = 'La data di partenza deve essere precedente alla data di rientro'
            }
            if (!end_date) {
                this.errors.end_date = 'La data di rientro è obbligatoria'
            } else if (end_date < start_date) {
                this.errors.end_date = 'La data di rientro deve essere successiva alla data di partenza'
            }
            console.log(this.errors.destination, this.errors.start_date, this.errors.end_date)
            return Object.keys(this.errors).length === 0;
        },
        //Funzione per calcolare la data corrente
        getCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
    }
}
</script>

<template>
    <div
        :class="['form-container d-flex align-items-center justify-content-center', { 'has-error': Object.keys(errors).length > 0 }]">
        <!-- Form per creare un nuovo viaggio -->
        <form @submit.prevent="submitForm">
            <div class="d-flex flex-wrap">
                <div class="d-flex mb-2 col-4">
                    <!-- Destinazione -->
                    <label for="destination" class="form-label mb-0 pe-2">Destinazione: </label>
                    <div>
                        <input class="rounded p-1 form-control" :class="{ 'is-invalid': this.errors.destination }"
                            id="destination" type="text" v-model="trip.destination" placeholder="Meta del viaggio">
                        <div class="invalid-feedback">{{ this.errors.destination }}</div>
                    </div>
                </div>
                <!-- Data di inizio -->
                <div class="d-flex gap-2 mb-2 col-4">
                    <label for="start" class="form-label mb-0 ps-3">Partenza: </label>
                    <div>
                        <input class="rounded p-1 form-control pe-5" :class="{ 'is-invalid': this.errors.start_date }"
                            id="start" type="date" v-model="trip.start_date">
                        <div class="invalid-feedback">{{ this.errors.start_date }}</div>
                    </div>
                </div>
                <!-- Data di fine -->
                <div class="d-flex gap-2 mb-2 col-4">
                    <label for="end" class="form-label mb-0 ps-3">Rientro: </label>
                    <div>
                        <input class="rounded form-control p-1 pe-5" :class="{ 'is-invalid': this.errors.end_date }"
                            id="end" type="date" v-model="trip.end_date">
                        <div class="invalid-feedback">{{ this.errors.end_date }}</div>

                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center mt-4">
                <button class="rounded p-1" type="submit">Crea Viaggio</button>
            </div>
        </form>
    </div>
</template>

<style lang="scss">
.form-container {
    height: 200px;
    width: 1000px;
    background-color: rgba(200, 200, 200, 0.5);
    border-radius: 10px;

    // Aumento le dimensioni del form-container in caso di errori
    &.has-error {
        height: 300px;
    }

    form {
        height: 40px;
    }
}
</style>