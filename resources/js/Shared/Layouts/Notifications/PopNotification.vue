<script setup>
import { useNotificationStore } from "@/Store/notification.js";

import { Link, router, useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";
import { formatDate } from "@/Helpers/date.js";
import axios from "axios";

const notifStore = useNotificationStore();

const appBaseUrl = usePage().props.appBaseUrl;

const notifications = computed(() => notifStore.notifications);
const notifCount = computed(() => notifStore.count);

const urlReadNotif = appBaseUrl + "/notifications";

const form = useForm({
    _method: "post",
});

setInterval(() => {
    notifStore.reloadNotification();
    notifStore.reloadCount();
}, 30 * 1000);

onMounted(() => {
    notifStore.reloadNotification();
    notifStore.reloadCount();
});

const onClickRead = (item) => {
    form._method = "put";
    axios.put(urlReadNotif + "/" + item.id).then(() => {
        notifStore.reloadNotification();
        notifStore.reloadCount();
    });
    router.visit(item.data.link);
};

const onClickMarkAsRead = () => {
    axios.post(urlReadNotif + "/read-all").then(() => {
        notifStore.reloadNotification();
        notifStore.reloadCount();
    });
};
</script>

<template>
    <div
        class="dropdown-menu dropdown-menu-end"
        aria-labelledby="navbarDropdown"
    >
        <div class="px-3">
            <h6
                v-if="notifCount > 0"
                @click="onClickMarkAsRead"
                class="mark-as-read text-secondary float-end d-none d-md-block"
            >
                Mark all as read
            </h6>
            <h5>Notifications</h5>
        </div>
        <div class="dropdown-divider"></div>

        <div
            v-if="notifications.length > 0"
            v-for="(item, index) in notifications"
            :key="index"
            class="dropdown-item text-wrap d-flex align-item-start"
            @click="onClickRead(item)"
        >
            <div
                class="notif-icon me-3"
                :class="{ 'bg-read': item.isRead, 'bg-unread': !item.isRead }"
            >
                <span v-if="item.isRead" class="material-icons"> drafts </span>
                <span v-else class="material-icons"> markunread</span>
            </div>
            <div class="notif-content">
                <div v-html="item.description"></div>
                <div class="text-secondary">
                    {{ formatDate(item.created_at) }}
                </div>
            </div>
        </div>
        <div v-else class="dropdown-item text-wrap">
            There is no notification
        </div>
        <div class="dropdown-divider"></div>
        <div v-if="notifications.length > 0" class="text-center py-1">
            <Link href="/notifications" class="fw-bold text-secondary"
                >All notifications</Link
            >
        </div>
    </div>
</template>

<style scoped>
.mark-as-read {
    cursor: pointer;
}
.dropdown-item {
    width: 500px;
    cursor: pointer;
}

@media (max-width: 768px) {
    .dropdown-item {
        width: 250px;
    }
}
</style>
