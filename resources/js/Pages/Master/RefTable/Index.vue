<script setup>
import { Head, Link } from "@inertiajs/vue3";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

const props = defineProps({
    arrRefLink: Array,
});

const breadcrumbs = [
    {
        url: "#",
        label: "Location",
    },
];
</script>

<template>
    <Head>
        <title>Location List</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="list-group">
                            <Link
                                v-for="refLink in arrRefLink"
                                :key="refLink.link"
                                :href="refLink.link"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                            >
                                {{ refLink.title }}
                                <i class="fas fa-chevron-right"></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
