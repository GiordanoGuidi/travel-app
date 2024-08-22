<script>
export default {
    name: 'AddStopForm',
    props: { stop: Object },
    emit: ['remove-stop', 'submit-form'],
}
</script>

<template>
    <form @submit.prevent="submitForm">
        <input v-model="stop.title" placeholder="Titolo della tappa">
        <input v-model="stop.description" placeholder="Descrizione della tappa">
        <!--Bottone per rimuovere la tappa-->
        <button @click="$emit('remove-stop')">Rimuovi tappa</button>
        <button @click="$emit('submit-form', stop)">Aggiungi</button>
    </form>
</template>
