<script>
export default {
    name: 'AddStopForm',
    props: { stop: Object, day: Number },
    emit: ['close-form', 'submit-form'],
    data: () => ({
        placeholder: 'placeholder.jpg',
        url: 'placeholder.jpg',
    }),
    methods: {
        //Funzione per mostrare la preview dell'immagine della tappa
        getImagePreview() {
            const inpuField = document.getElementById('image');
            const inputValue = inpuField.value;
            const imgField = document.getElementById('preview');
            if (inpuField.files && inpuField.files[0]) {
                //prendo l'oggetto file
                let file = inpuField.files[0]
                this.stop.image = file.name;
                //Preparo un url temporaneo
                const blobUrl = URL.createObjectURL(file);
                imgField.src = blobUrl;
            } else {
                imgField.src = placeholder;
            }
        },
        submitForm() {
            console.log("URL before emitting:", this.url);
            this.$emit('submit-form', this.stop, this.url, this.day);
        }
    }
}
</script>

<template>
    <form @submit.prevent="submitForm">
        <div class="row d-flex gap-2 flex-wrap">
            <!-- Titolo della tappa -->
            <div class="col-4">
                <label for="title">Title</label>
                <input v-model="stop.title" placeholder="Titolo della tappa" class="form-control" name="title"
                    id="title">
                <div class="invalid-feedback">
                </div>
            </div>
            <!-- Descrizione della tappa -->
            <div class="col-7">
                <label for="description">Description</label>
                <textarea v-model="stop.description" class="form-control col-6" placeholder="Descrizione della tappa"
                    name="description" id="description" cols="20" rows=""></textarea>
                <div class="invalid-feedback">
                </div>
            </div>
        </div>
        <!-- Immagine della tappa -->
        <div class="row d-flex flex-wrap mt-3">
            <div class="col-10">
                <label for="image" class="form-label">Image</label>
                <input type="file" @change="getImagePreview" class="form-control" id="image" name="image" value="">
                <!-- <div class="invalid-feedback">
                </div> -->
                <div class="form text text-muted">
                    Inserisci un immagine di formato PNG, JPG o JPEG.
                    <p>{{ url }}</p>
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
                <button class="btn btn-success" @click="submitForm">Aggiungi</button>
            </div>
        </div>
    </form>
</template>
