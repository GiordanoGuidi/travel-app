<script>
import AppHeader from "./components/AppHeader.vue";


export default {
  name: 'travel-app',
  components: { AppHeader },
}
</script>

<template>
  <div class="wrapper">
    <!-- Header -->
    <AppHeader />
    <main>
      <RouterView />
    </main>
  </div>
</template>

<style lang="scss">
@use './assets/scss/style.scss';
</style>
