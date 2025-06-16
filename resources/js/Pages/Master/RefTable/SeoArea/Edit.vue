<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VAlert from "@/Shared/VAlert.vue";
import VDevider from "@/Shared/VDevider.vue";

import VForm from "./_partials/VForm.vue";
import VHeaderButtonInfo from "@/Shared/HeaderButton/VButtonInfo.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const {
    data,
    groups,
    filters,
    canView,

    urlRefTableIndex,
    urlUpdate,
    urlShow,
    urlIndex,
} = props.additional;

const breadcrumbs = [
    {
        url: urlRefTableIndex,
        label: "Reference Table Management",
    },
    {
        url: urlIndex,
        label: "SEO Area",
    },
    {
        url: "#",
        label: "Edit SEO Area",
    },
];

const initialValue = {
    code: data.code,
    description: data.description,
    ref_seo_group_id: data.ref_seo_group_id,
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
                    <VTitleWithBackLink :href="urlIndex" :filters="filters ?? {}">
                        Edit SEO Area
                    </VTitleWithBackLink>
                    <div class="btn-wrapper">
                        <VHeaderButtonInfo v-if="canView" :href="urlShow" />
                    </div>
                </div>
                <VDevider class="mb-4" />
                <VAlert />
                <VForm :initialValue="initialValue" :urlSubmit="urlUpdate" method="PUT" :groups="groups" />
            </div>
        </div>
    </div>
</template>
