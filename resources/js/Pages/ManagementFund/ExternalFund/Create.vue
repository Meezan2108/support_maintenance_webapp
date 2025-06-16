<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VAlert from "@/Shared/VAlert.vue";
import VDevider from "@/Shared/VDevider.vue";

import VForm from "./_partials/VForm.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const breadcrumbs = [
    {
        url: props.additional.urlIndex,
        label: "External Fund",
    },
    {
        url: "#",
        label: "New Proposal",
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
                    :urlSubmit="additional.urlStore"
                    :refBenefits="additional.refBenefits"
                    :refProjectCostSeriesDirect="
                        additional.refProjectCostSeriesDirect
                    "
                    type="create"
                    method="POST"
                />
            </div>
        </div>
    </div>
</template>
