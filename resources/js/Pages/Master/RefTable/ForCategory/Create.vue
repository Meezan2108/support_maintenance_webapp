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

    urlRefTableIndex,
    urlStore,
    urlIndex,
} = props.additional;

const breadcrumbs = [
    {
        url: urlRefTableIndex,
        label: "Location",
    },
    {
        url: urlIndex,
        label: "Location List",
    },
    {
        url: "#",
        label: "Add New Location",
    },
];

const initialValue = {
    code: "",
    description: "",
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
                <div class="d-flex justify-content-between">
                    <VTitleWithBackLink
                        :href="urlIndex"
                        :filters="filters ?? {}"
                    >
                        Add New Location
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <VAlert />

                <VForm
                    :initialValue="initialValue"
                    :urlSubmit="urlStore"
                    method="POST"
                />
            </div>
        </div>
    </div>
</template>
