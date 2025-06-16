<script setup>
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import debounce from "lodash/debounce";

import Datatables from "@/Shared/Tables/Datatables.vue";
import DatatableFooterWrapper from "@/Shared/Tables/DatatableFooterWrapper.vue";

import VAlert from "@/Shared/VAlert.vue";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VButtonCreate from "@/Shared/HeaderButton/VButtonCreate";

let props = defineProps({
    roles: Object,
    filters: Object,
    columns: Array,
    canCreate: Boolean,
    urlCreate: String,
});

const breadcrumbs = [
    {
        url: "#",
        label: "Role Management",
    },
];

const changePageLength = (value) => {
    geRoles({ per_page: value });
};

const changeOrder = (value) => {
    geRoles({
        per_page: props.filters.per_page ?? 20,
        order_by: value.order_by,
        order_type: value.order_type,
    });
};

const geRoles = (params) => {
    router.get("/role", params, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <Head>
        <title>Role Management</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <VAlert />

        <div class="card">
            <div class="card-body">
                <div class="text-end mb-3">
                    <VButtonCreate :href="urlCreate"> Add Roles </VButtonCreate>
                </div>

                <div class="dataTables_wrapper dt-bootstrap5">
                    <Datatables
                        :columns="columns"
                        :pagination="roles"
                        :filters="filters"
                        @clickOrder="changeOrder"
                    />

                    <DatatableFooterWrapper
                        :pagination="roles.meta"
                        :filters="filters"
                        @onChange="changePageLength"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
