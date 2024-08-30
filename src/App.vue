<script>
import AppHeader from "./components/AppHeader.vue";
import AppLoader from "./components/AppLoader.vue";
import { store } from './data/store';


export default {
  name: 'travel-app',
  data: () => ({
    store
  }),
  components: { AppHeader, AppLoader },
}
</script>

<template>
  <div class="wrapper">
    <!-- Header -->
    <AppHeader />
    <!-- Loader -->
    <AppLoader v-if="store.isLoading" />
    <main>
      <RouterView />
    </main>
  </div>
</template>

<style lang="scss">
@use './assets/scss/style.scss';
</style>
