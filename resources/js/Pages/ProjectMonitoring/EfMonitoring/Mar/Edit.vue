<script setup>
import { Head, router } from "@inertiajs/vue3";

import VAlert from "@/Shared/VAlert.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VForm from "./_partials/VForm.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, initValue, projectNumbers } = props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Report Progress External Fund",
    },
    {
        url: "#",
        label: "Edit Milestone Achievement Report (MAR)",
    },
];
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <VAlert />

        <div class="card">
            <div class="card-body">
                <VForm
                    :initActiveTab="additional.initActiveTab"
                    :initValue="initValue"
                    :projectNumbers="projectNumbers"
                    formType="edit"
                    method="PUT"
                />
            </div>
        </div>
    </div>
</template>
