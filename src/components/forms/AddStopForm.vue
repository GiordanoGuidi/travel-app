<script>
import { store } from '../../data/store';
//regex per controllare che la stringa contenga numeri e lettere
const regex = /^[A-Z0-9 _]*[A-Z0-9][A-Z0-9 _]*$/;


export default {
    name: 'AddStopForm',
    props: { stop: Object, day: Number },
    emit: ['close-form', 'submit-form'],
    data: () => ({
        imagePreview: '../../../public/placeholder.jpg',
        errors: {},
        endpoint: store.endpoint,
        isValidAddress: null,
    }),
    methods: {
        //Funzione per mostrare la preview dell'immagine della tappa
        getImagePreview() {
            const inpuField = document.getElementById('image');
            // const imgField = document.getElementById('preview');
            //Svuoto l'array di errori
            this.errors = {};
            //Controllo se il file esiste
            if (inpuField.files && inpuField.files[0]) {
                //prendo l'oggetto file
                let file = inpuField.files[0]
                //Validazione del file inserito
                //Creo un array di MIME valide
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg']
                //Creo un array di estensioni valide
                const validExtensions = ['jpeg', 'jpg', 'png'];
                const fileExtensions = file.name.split('.').pop().toLowerCase();
                // Controllo se la MIME del file sia contenute in quelle valide
                if (!validTypes.includes(file.type)) {
                    this.errors.image = 'Inserisci un file di tipo jpeg,png,jpg';
                }
                //Controllo che l'estensione del file sia contenute in quelle valide
                else if (!validExtensions.includes(fileExtensions)) {
                    this.errors.image = 'Inserisci un file di tipo jpeg,png,jpg'
                } else {
                    // Se esiste già un URL temporaneo, lo revoco per risparmiare memoria
                    if (this.imagePreview) {
                        URL.revokeObjectURL(this.imagePreview);
                    }
                    //Creo un url temporaneo
                    this.imagePreview = URL.createObjectURL(file);
                    const formData = new FormData();
                    //aggiungo il file all'oggetto formData nella chiave 'image'
                    formData.append('image', file);
                    fetch(`${this.endpoint}/upload.php`, {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.imagePreview = `${this.endpoint}/${data.filePath}`;
                                this.stop.image = `${this.endpoint}/${data.filePath}`;
                            } else {
                                this.errors.image = data.message;
                            }
                        })
                        .catch(error => {
                            console.error('Errore:', error);
                            this.errors.image = 'Errore nel caricamento del file';
                        });
                }
            }
        },
        //Metodo per comunicare che il form è stato inviato
        async submitForm() {
            //attendo il risultato della validazione
            if (await this.validateForm()) {
                this.$emit('submit-form', this.stop, this.day, this.imagePreview);
            } else {
                console.log('Form non valido');
            }
        },
        //Funzione per validare i campi del form di aggiunta di una tappa
        async validateForm() {
            store.isLoading = true;
            const { title, description, image, address } = this.stop;
            this.errors = {};
            if (!title) {
                this.errors.title = 'Il titolo è obbligatorio'
            }
            else if (!isNaN(title)) {
                this.errors.title = 'Il titolo non può essere un numero'
            }
            else if (title.trim().length < 2) {
                this.errors.title = 'Il titolo deve contenere almeno 2 caratteri'
            }
            if (!description.trim()) {
                this.errors.description = 'La descrizione è obbligatoria'
            } else if (!isNaN(description)) {
                this.errors.description = 'La descrizione non può essere un numero'
            } else if (description.trim().length < 2) {
                this.errors.description = 'La descrizione deve contenere almeno 2 caratteri'
            }
            if (!isNaN(image)) {
                this.errors.image = 'L\'immagine non può essere un numero'
            }
            if (!address) {
                this.errors.address = 'L\'indirizzo è obbligatorio'
            } else if (!isNaN(address)) {
                this.errors.address = 'L\'indirizzo non può essere un numero'
            } else if (address.trim().length < 5) {
                this.errors.address = 'L\'indirizzo deve contenere almeno 5 caratteri'
            } else if (regex.test(address)) {
                this.errors.address = 'L\'indirizzo deve contenere numeri e lettere'
            } else {
                // Chiama la funzione checkAddress in modo asincrono
                const isValidAddress = await this.checkAddress(address);
                console.log(isValidAddress);
                if (!isValidAddress) {
                    this.errors.address = 'Nessuna posizione trovata per l\'indirizzo fornito.';
                }
            }
            console.log(this.errors);
            store.isLoading = false;
            console.log(Object.keys(this.errors).length === 0);
            //Se ci sono errori il return sara false altrimenti sarò true
            return Object.keys(this.errors).length === 0;
        },
        //Controllo che l'indirizzo esista
        async checkAddress(address) {
            try {
                const apiKey = import.meta.env.VITE_API_KEY;
                console.log(apiKey);
                const response = await fetch(`https://api.tomtom.com/search/2/geocode/${encodeURIComponent(address)}.json?key=${apiKey}`)
                const data = await response.json();
                if (data.results && data.results.length > 0) {
                    console.log(data.results);
                    return true;
                } else {
                    console.log(data.results);
                    this.errors.address = 'Nessuna posizione trovata per l\'indirizzo fornito.';
                    console.error('Nessuna posizione trovata per l\'indirizzo fornito.');
                    return false;
                }
            } catch (error) {
                console.error('Errore nel geocoding:', error);
                this.errors.address = 'Errore nel geocoding.';
                return false;
            }
        }
    },
}
</script>

<template>
    <!-- Form per aggiungere una tappa -->
    <form @submit.prevent="submitForm">
        <div class="row d-flex gap-2 flex-wrap">
            <!-- Titolo della tappa -->
            <div class="col-4">
                <label for="title">Title</label>
                <input v-model="stop.title" placeholder="Titolo della tappa" class="form-control"
                    :class="{ 'is-invalid': this.errors.title }" name="title" id="title">
                <!-- Messaggio di errore nel campo -->
                <div class="invalid-feedback">
                    {{ this.errors.title }}
                </div>
            </div>
            <!-- Descrizione della tappa -->
            <div class="col-7">
                <label for="description">Description</label>
                <textarea v-model="stop.description" class="form-control col-6"
                    :class="{ 'is-invalid': this.errors.description }" placeholder="Descrizione della tappa"
                    name="description" id="description" cols="20" rows=""></textarea>
                <!-- Messaggio di errore nel campo -->
                <div class="invalid-feedback">
                    {{ this.errors.description }}
                </div>
            </div>
        </div>
        <!-- Immagine della tappa -->
        <div class="row d-flex flex-wrap mt-3">
            <div class="col-4">
                <label for="image" class="form-label">Image</label>
                <input type="file" @change="getImagePreview" class="form-control"
                    :class="{ 'is-invalid': this.errors.image }" id="image" name="image" value="">
                <div class="invalid-feedback">
                    {{ this.errors.image }}
                </div>
                <div v-if="!this.errors.image" class="form text text-muted">
                    Inserisci un immagine di formato PNG, JPG o JPEG.
                </div>
            </div>
            <!-- Indirizzo della tappa -->
            <div class="col-4">
                <label for="address" class="form-label">Address</label>
                <input v-model="stop.address" type="text" class="form-control"
                    :class="{ 'is-invalid': this.errors.address }" placeholder="Indirizzo" id="address" name="address">
                <div class="invalid-feedback">
                    {{ this.errors.address }}
                </div>
            </div>
            <!-- Preview immagine della tappa -->
            <div class="col-2">
                <div class="mb-3">
                    <img :src="imagePreview" alt="#" class="img-fluid" id="preview">
                </div>
            </div>
            <div class="col-6 d-flex gap-3 mt-3">
                <!-- Bottone per chiudere il form  -->
                <button class="btn btn-danger" @click="$emit('close-form')">Rimuovi</button>
                <!-- Bottone per aggiungere la tappa -->
                <button class="btn btn-success" type="submit">Aggiungi</button>
            </div>

        </div>
    </form>
</template>
