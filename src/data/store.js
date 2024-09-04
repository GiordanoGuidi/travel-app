import { reactive } from 'vue';
export const store = reactive({
    trip: {
        destination: '',
        duration: '',
        start_date: '',
    },
    isLoading: false,
    //Endpoint locale
    endpoint: "http://localhost:8888/boolean/travel-app/backend",
})