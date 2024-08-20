<script>
export default {
    name: 'AppHeader',
}
</script>

<template>
    <header>
        <h1 class="text-center">Travel App</h1>
        <ul class="d-flex list-unstyled justify-content-center gap-5">
            <RouterLink :to="{ name: 'create-trip' }">
                <li>Crea il tuo viaggio</li>
            </RouterLink>
            <li>I tuoi viaggi</li>
        </ul>
    </header>
</template>