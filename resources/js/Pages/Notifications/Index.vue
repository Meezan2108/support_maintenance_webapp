<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";

import { useNotificationStore } from "@/Store/notification.js";

import VDevider from "@/Shared/VDevider.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import DatatablePagination from "@/Shared/Tables/DatatablePagination.vue";
import { formatDate } from "@/Helpers/date.js";
import { computed } from "vue";
import axios from "axios";

let props = defineProps({
    title: String,
    additional: Object,
});

const notifStore = useNotificationStore();

const appBaseUrl = usePage().props.appBaseUrl;

const data = computed(() => props.additional.data);
const urlReadNotif = appBaseUrl + "/notifications";

const breadcrumbs = [
    {
        url: "#",
        label: "Notifications",
    },
];

const onClickRead = (item) => {
    axios.put(urlReadNotif + "/" + item.id).then(() => {
        notifStore.reloadCount();
        router.visit(item.data.link);
    });
};

const onClickMarkAsRead = () => {
    axios.post(urlReadNotif + "/read-all").then(() => {
        notifStore.reloadCount();
        router.reload();
    });
};
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <h5 class="d-flex align-items-center">Notifications</h5>

                <VDevider class="mb-4" />

                <div class="row justify-content-center my-3">
                    <div class="col-12 col-lg-8">
                        <div class="text-end">
                            <h6
                                v-if="notifStore.count > 0"
                                @click="onClickMarkAsRead"
                                class="text-secondary mb-4"
                                role="button"
                            >
                                Mark all as read
                            </h6>
                        </div>
                        <div
                            v-for="(item, index) in data.data"
                            :key="index"
                            class="d-flex align-item-start mb-3 text-decoration-none text-dark"
                            role="button"
                            @click="onClickRead(item)"
                        >
                            <div
                                class="notif-icon me-3"
                                :class="{
                                    'bg-read': item.isRead,
                                    'bg-unread': !item.isRead,
                                }"
                            >
                                <span v-if="item.isRead" class="material-icons">
                                    drafts
                                </span>
                                <span v-else class="material-icons">
                                    markunread</span
                                >
                            </div>
                            <div class="notif-content">
                                <div v-html="item.description"></div>
                                <div class="text-secondary">
                                    {{ formatDate(item.created_at) }}
                                </div>
                            </div>
                            <div class="ms-auto">
                                <span class="material-icons">east</span>
                            </div>
                        </div>
                        <DatatablePagination :pagination="data.meta" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
