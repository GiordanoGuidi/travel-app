import { reactive } from 'vue';
export const store = reactive({
    trip: {
        destination: '',
        duration: '',
        start_date: '',
    },
    isLoading: false,
})