<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VAlert from "@/Shared/VAlert.vue";
import VDevider from "@/Shared/VDevider.vue";

import VForm from "./_partials/VForm.vue";
import { computed } from "vue";

const props = defineProps({
    title: String,
    additional: Object
});

const {
    categories,

    urlRefTableIndex,
    urlStore,
    urlIndex,
} = props.additional;

const filters = computed(() => props.additional.filters);


const breadcrumbs = [
    {
        url: urlRefTableIndex,
        label: "Reference Table Management",
    },
    {
        url: urlIndex,
        label: "SEO Group",
    },
    {
        url: "#",
        label: "Add New",
    },
];

const initialValue = {
    code: "",
    description: "",
    ref_seo_category_id: null,
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
                        Create SEO Group
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <VAlert />
                <VForm :initialValue="initialValue" :urlSubmit="urlStore" method="POST" :categories="categories" />
            </div>
        </div>
    </div>
</template>
