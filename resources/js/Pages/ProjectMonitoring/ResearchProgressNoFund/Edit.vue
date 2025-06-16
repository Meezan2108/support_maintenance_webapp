<script setup>
import { Head, router } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";
import VAlert from "@/Shared/VAlert.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VForm from "./_partials/VForm.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, urlSubmit, initValue, projectTitles, reportTypes } =
    props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Research Progress Report (No Fund)",
    },
    {
        url: "#",
        label: "Edit Report",
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
                <h3>Research Progress Report</h3>
                <VDevider class="my-3" />

                <VForm
                    :initValue="initValue"
                    :projectTitles="projectTitles"
                    :reportTypes="reportTypes"
                    :urlSubmit="urlSubmit"
                    formType="edit"
                    method="PUT"
                />
            </div>
        </div>
    </div>
</template>
