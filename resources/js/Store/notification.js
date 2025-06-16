import { defineStore } from "pinia";
import { ref, watch } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";

export const useNotificationStore = defineStore("notifications", () => {
    const notifications = ref([]);
    const count = ref(0);

    const appBaseUrl = usePage().props.appBaseUrl;

    function reset() {
        count.value = 0;
        notifications.value = [];
    }

    function setNotif(data) {
        notifications.value = data;
    }

    function setCount(data) {
        count.value = data;
    }

    async function reloadCount() {
        const response = await axios.get(`${appBaseUrl}/resources/notifications/count`);
        const { data } = response.data;

        setCount(data);
        return data;
    }

    async function reloadNotification() {
        const response = await axios.get(`${appBaseUrl}/resources/notifications`);
        const { data } = response.data;

        setNotif(data);
        return data;
    }

    watch(count, (newValue) => reloadNotification());

    return {
        notifications,
        count,
        reset,
        setNotif,
        setCount,
        reloadCount, 
        reloadNotification
    };
});
