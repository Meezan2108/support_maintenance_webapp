<script setup>
import { Head, router } from "@inertiajs/vue3";

import Datatables from "@/Shared/Tables/Datatables.vue";
import DatatableFooterWrapper from "@/Shared/Tables/DatatableFooterWrapper.vue";

import VAlert from "@/Shared/VAlert.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VButtonCreate from "@/Shared/HeaderButton/VButtonCreate";
import VButton from "@/Shared/HeaderButton/VButton";
import { computed } from "vue";

let props = defineProps({
    title: String,
    additional: Object,
});

const {
    columns,
    canCreate,
    canCreateBulk,

    urlUploadBulk,
    urlCreate,
    urlIndex,
} = props.additional;

const list = computed(() => props.additional.list);
const filters = computed(() => props.additional.filters);

const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: "#",
        label: "R&D Output",
    },
];

const changePageLength = (value) => {
    getList({ per_page: value });
};

const onFilter = (value) => {
    getList({
        per_page: filters.per_page ?? 20,
        order_by: value.order_by,
        order_type: value.order_type,
        search_fields: value.search_fields,
        search_values: value.search_values,
    });
};

const getList = (params) => {
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
                <div class="text-end mb-3">
                    <VButton
                        v-if="canCreateBulk"
                        :href="urlUploadBulk"
                        class="me-2"
                    >
                        <span class="material-icons me-1">upload</span>
                        Upload Bulk
                    </VButton>

                    <VButtonCreate v-if="canCreate" :href="urlCreate">
                        Add R&D Outputs
                    </VButtonCreate>
                </div>

                <div class="dataTables_wrapper dt-bootstrap5">
                    <Datatables
                        :columns="columns"
                        :pagination="list"
                        :filters="filters"
                        @onFilter="onFilter"
                    />

                    <DatatableFooterWrapper
                        :pagination="list.meta"
                        :filters="filters"
                        @onChange="changePageLength"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
