<script>
const endpoint = 'http://localhost:8888/boolean/travel-app-back';
export default {
    name: 'StopDetails',
    methods: {
        getStop() {
            fetch(`${endpoint}/get_stop.php?id=${this.$route.params.id}
            &stop_id=${this.route.params.stop_id}&stop_day=${this.route.params.stop_day}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
            })
        }
    }
}
</script>
<template>
    <section id="stop-details">
        <h1 class="text-white">Stop Details</h1>
        <!-- <p>{{ stop.title }}</p>s -->
    </section>

</template>