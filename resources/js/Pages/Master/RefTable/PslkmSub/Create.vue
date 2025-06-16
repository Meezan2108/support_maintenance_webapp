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

const {
    filters,

    urlRefTableIndex,
    urlStore,
    urlIndex,
    urlResourcePslkm,

    arrStatus,
} = props.additional;

const breadcrumbs = [
    {
        url: urlRefTableIndex,
        label: "Reference Table Management",
    },
    {
        url: urlIndex,
        label: "Sub PSLKM",
    },
    {
        url: "#",
        label: "Add New Sub PSLKM",
    },
];

const initialValue = {
    code: "",
    ref_pslkm_id: "",
    description: "",
    status: 1,
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
                        Create Sub PSLKM
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <VAlert />
                <VForm
                    :initialValue="initialValue"
                    :urlSubmit="urlStore"
                    :arrStatus="arrStatus"
                    :urlResourcePslkm="urlResourcePslkm"
                    method="POST"
                />
            </div>
        </div>
    </div>
</template>
