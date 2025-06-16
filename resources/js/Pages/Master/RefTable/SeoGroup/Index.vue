<script setup>
import { Head, Link, router } from "@inertiajs/vue3";

import Datatables from "@/Shared/Tables/Datatables.vue";
import DatatableFooterWrapper from "@/Shared/Tables/DatatableFooterWrapper.vue";
import VAlert from "@/Shared/VAlert.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VButtonCreate from "@/Shared/HeaderButton/VButtonCreate";
import { computed } from "vue";

let props = defineProps({
    title: String,
    additional: Object
});

const {
    columns,
    canCreate,

    urlRefTableIndex,
    urlCreate,
    urlIndex,
} = props.additional;

const data = computed(() => props.additional.data);
const filters = computed(() => props.additional.filters);

const breadcrumbs = [
    {
        url: urlRefTableIndex,
        label: "Reference Table Management",
    },
    {
        url: "#",
        label: "SEO Group",
    },
];

const changePageLength = (value) => {
    getAreas({ per_page: value });
};

const onFilter = (value) => {
    getData({
        per_page: filters.per_page ?? 20,
        order_by: value.order_by,
        order_type: value.order_type,
        search_fields: value.search_fields,
        search_values: value.search_values,
    });
};

const getData = (params) => {
    router.get(urlIndex, params, {
        preserveState: true,
        replace: true,
    });
};
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
                <div v-if="canCreate" class="text-end mb-3">
                    <VButtonCreate :href="urlCreate">
                        Add SEO Group
                    </VButtonCreate>
                </div>

                <div class="dataTables_wrapper dt-bootstrap5">
                    <Datatables :columns="columns" :pagination="data" :filters="filters" @onFilter="onFilter" />

                    <DatatableFooterWrapper :pagination="data.meta" :filters="filters" @onChange="changePageLength" />
                </div>
            </div>
        </div>
    </div>
</template>
