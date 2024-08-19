<script>
import AppHeader from "./components/AppHeader.vue";
import AppForm from "./components/AppForm.vue";

export default {
  name: 'travel-app',
  components: { AppHeader, AppForm },
  data: () => ({
    trip: {
      destination: '',
      duration: '',
      start_date: '',
    }
  }),
  methods: {
    createTrip() {
      fetch('http://localhost:8888/boolean/travel-app-back/create_trip.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.trip)
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Errore nella richiesta al server');
          }
          return response.json();
        })
        .then(data => {
          if (data.status === 'success') {
            alert('Viaggio creato con successo!');
          } else {
            alert('Errore nella creazione del viaggio.');
          }
        })
        .catch(error => {
          console.error('Errore:', error);
          alert('Si è verificato un errore. Controlla la console per maggiori dettagli.');
        });
    }
  }

}
</script>

<template>
  <!-- Header -->
  <AppHeader />
  <!-- Form -->
  <AppForm :trip="trip" @submit-trip="createTrip" />


</template>
