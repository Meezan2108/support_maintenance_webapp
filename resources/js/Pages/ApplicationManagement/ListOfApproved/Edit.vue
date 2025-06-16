<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VAlert from "@/Shared/VAlert.vue";

import VForm from "./_partials/VForm.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const breadcrumbs = [
    {
        url: props.additional.urlIndex,
        label: "List of Approved Project",
    },
    {
        url: "#",
        label: "Edit Proposal",
    },
];
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <VAlert />
                <VForm
                    :initValue="additional.initValue"
                    :initActiveTab="additional.activeTab"
                    :urlBase="additional.urlBase"
                    :refBenefits="additional.refBenefits"
                    :refProjectCostSeriesDirect="
                        additional.refProjectCostSeriesDirect
                    "
                    :arrProjectStatus="additional.arrProjectStatus"
                    type="edit"
                    method="PUT"
                />
            </div>
        </div>
    </div>
</template>
