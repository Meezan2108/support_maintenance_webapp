<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VAlert from "@/Shared/VAlert.vue";
import VDevider from "@/Shared/VDevider.vue";

import VForm from "./_partials/VForm.vue";

const props = defineProps({
    title: String,
    additional: Object
});

const {
    filters,
    groups,

    urlRefTableIndex,
    urlStore,
    urlIndex,
} = props.additional;

const breadcrumbs = [
    {
        url: urlRefTableIndex,
        label: "Reference Table Management",
    },
    {
        url: urlIndex,
        label: "For Area",
    },
    {
        url: "#",
        label: "Add New FOR Area",
    },
];

const initialValue = {
    code: "",
    description: "",
    ref_for_group_id: null,
};
</script>

<template>
    <Head>
        <title>Create - FOR Area</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <VTitleWithBackLink :href="urlIndex" :filters="filters ?? {}">
                        Create FOR Area
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <VAlert />
                <VForm :initialValue="initialValue" :urlSubmit="urlStore" method="POST" :groups="groups" />
            </div>
        </div>
    </div>
</template>
