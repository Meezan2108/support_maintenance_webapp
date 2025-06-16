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
    users: Object,
    filters: Object,
    columns: Array,
    canCreate: Boolean,

    urlCreate: String,
    urlIndex: String,
});

const breadcrumbs = [
    {
        url: "#",
        label: "User Management",
    },
];

const changePageLength = (value) => {
    getUser({ per_page: value });
};

const onFilter = (value) => {
    getUser({
        search_fields: value.search_fields,
        search_values: value.search_values,
        per_page: props.filters.per_page ?? 20,
        order_by: value.order_by,
        order_type: value.order_type,
    });
};

const getUser = (params) => {
    router.get(props.urlIndex, params, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <Head>
        <title>User Management</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <VAlert />

        <div class="card">
            <div class="card-body">
                <div class="text-end mb-3" v-if="canCreate">
                    <VButtonCreate :href="urlCreate"> Add User </VButtonCreate>
                </div>

                <div class="dataTables_wrapper dt-bootstrap5">
                    <Datatables
                        :columns="columns"
                        :pagination="users"
                        :filters="filters"
                        @onFilter="onFilter"
                    />
                    <DatatableFooterWrapper
                        :pagination="users.meta"
                        :filters="filters"
                        @onChange="changePageLength"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
