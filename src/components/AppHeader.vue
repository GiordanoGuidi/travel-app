<script>
export default {
    name: 'AppHeader',
}
</script>

<template>
    <header>
        <h1 class="text-center">Travel App</h1>
        <ul class="d-flex list-unstyled justify-content-center gap-5">
            <!-- Rotta per la Homepage -->
            <RouterLink :to="{ name: 'home-page' }">
                <li>Home</li>
            </RouterLink>
            <!-- Rotta per il form di creazione viaggio -->
            <RouterLink :to="{ name: 'create-trip' }">
                <li>Crea il tuo viaggio</li>
            </RouterLink>
            <li>I tuoi viaggi</li>
        </ul>
    </header>
</template>

<style scoped lang="scss"></style>