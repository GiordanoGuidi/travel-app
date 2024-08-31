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
            <RouterLink :to="{ name: 'home-page' }" class="text-decoration-none text-dark">
                <li>Home</li>
            </RouterLink>
            <!-- Rotta per il form di creazione viaggio -->
            <RouterLink :to="{ name: 'create-trip' }" class="text-decoration-none text-dark">
                <li>Crea il tuo viaggio</li>
            </RouterLink>
            <RouterLink :to="{ name: 'trip-list' }" class="text-decoration-none text-dark">
                <li>I tuoi viaggi</li>
            </RouterLink>
        </ul>
    </header>
</template>

<style scoped lang="scss"></style>