<script>
import AppHeader from "./components/AppHeader.vue";


export default {
  name: 'travel-app',
  components: { AppHeader },
}
</script>

<template>
  <!-- Header -->
  <AppHeader />
  <main>
    <RouterView />
  </main>
</template>

<style lang="scss">
@use './assets/scss/style.scss';
</style>
