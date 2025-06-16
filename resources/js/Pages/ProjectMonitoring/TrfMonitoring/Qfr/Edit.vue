<script setup>
import { Head, router } from "@inertiajs/vue3";

import VAlert from "@/Shared/VAlert.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VForm from "./_partials/VForm.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, initValue, projectNumbers, projectCostSeries } =
    props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Report Progress Temporary Research Fund (TRF)",
    },
    {
        url: "#",
        label: "Edit Quarter Financial Report (QFR)",
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
        <div
            v-if="projectNumbers.length <= 0"
            class="alert alert-warning fade show"
            role="alert"
        >
            There are no project on going.
        </div>

        <div class="card">
            <div class="card-body">
                <VForm
                    :initActiveTab="additional.initActiveTab"
                    :initValue="initValue"
                    :projectNumbers="projectNumbers"
                    :projectCostSeries="projectCostSeries"
                    formType="edit"
                    method="PUT"
                />
            </div>
        </div>
    </div>
</template>
