<script>
export default {
    name: 'StopCard',
    props: { stop: Object }
}
</script>

<template>
    <li class="stop-card d-flex flex-column justify-content-between align-items-center w-100">
        <div class="d-flex justify-content-between align-items-center w-100">
            <!-- Titolo della tappa -->
            <p>{{ stop.title }}</p>
            <!-- Immagine della tappa -->
            <div class="img-container d-flex align-items-center">
                <img :src="stop.image" alt="">
            </div>
        </div>
    </li>
</template>
<style scoped lang="scss">
.stop-card {
    cursor: pointer;
    padding: 10px;
    background-color: rgba(220, 220, 220, 0.7);
    border-radius: 10px;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.25);


    &:hover {
        background-color: rgba(100, 100, 100, 0.7);
        transition: 1s;
    }
}

.img-container {
    width: 100px;
    height: 100px;

    img {
        max-width: 100%;
        max-height: 100%;
    }
}
</style>
