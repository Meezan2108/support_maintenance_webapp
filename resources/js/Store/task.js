import { defineStore } from "pinia";
import { ref } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";

export const useTaskStore = defineStore("task", () => {
    const count = ref(0);

    const appBaseUrl = usePage().props.appBaseUrl;

    function reset() {
        count.value = 0;
    }

    function setCount(data) {
        count.value = data;
    }

    async function checkCount() {
        const response = await axios.get(`${appBaseUrl}/resources/task/count`);
        const { data } = response.data;

        setCount(data);
        return data;
    }

    return { count, reset, checkCount, setCount };
});
