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

const { filters, user, initValue, projectNumbers, urlStore, urlIndex } =
    props.additional;

const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: urlIndex,
        label: "Imported Germplasm",
    },
    {
        url: "#",
        label: "Add New Imported Germplasm",
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
                <div class="d-flex justify-content-between">
                    <VTitleWithBackLink
                        :href="urlIndex"
                        :filters="filters ?? {}"
                    >
                        Create Imported Germplasm
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <VAlert />
                <VForm
                    :initValue="initValue"
                    :projectNumbers="projectNumbers"
                    :urlSubmit="urlStore"
                    :user="user"
                    method="POST"
                />
            </div>
        </div>
    </div>
</template>
