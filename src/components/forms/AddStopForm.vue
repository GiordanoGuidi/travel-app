<script>
export default {
    name: 'AddStopForm',
    props: { stop: Object, day: Number },
    emit: ['close-form', 'submit-form'],
    data: () => ({
        placeholder: 'placeholder.jpg',
        errors: {},

    }),
    methods: {
        //Funzione per mostrare la preview dell'immagine della tappa
        getImagePreview() {
            const inpuField = document.getElementById('image');
            const imgField = document.getElementById('preview');
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
                    this.stop.image = file.name;
                    //Preparo un url temporaneo
                    const blobUrl = URL.createObjectURL(file);
                    imgField.src = blobUrl;
                }
            } else {
                imgField.src = this.placeholder;
            }
        },
        //Metodo per comunicare che il form è stato inviato
        submitForm() {
            if (this.validateForm()) {
                this.$emit('submit-form', this.stop, this.day);
            }
        },
        //Funzione per validare i campi del form di aggiunta di una tappa
        validateForm() {
            const { title, description, image } = this.stop;
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
            //Se ci sono errori il return sara false altrimenti sarò true
            return Object.keys(this.errors).length === 0;
        },
    }
}
</script>

<template>
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
            <div class="col-10">
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
            <!-- Preview immagine della tappa -->
            <div class="col-2">
                <div class="mb-3">
                    <img :src="`../../../public/${placeholder}`" alt="#" class="img-fluid" id="preview">
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
