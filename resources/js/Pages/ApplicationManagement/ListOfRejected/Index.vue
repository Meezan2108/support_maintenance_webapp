<script setup>
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

import Datatables from "@/Shared/Tables/Datatables.vue";
import DatatableFooterWrapper from "@/Shared/Tables/DatatableFooterWrapper.vue";
import VAlert from "@/Shared/VAlert.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VButtonCreate from "@/Shared/HeaderButton/VButtonCreate";

let props = defineProps({
    title: String,
    additional: Object,
});

const breadcrumbs = [
    {
        url: "#",
        label: "List of Rejected Proposal",
    },
];

const changePageLength = (value) => {
    getData({ per_page: value });
};

const onFilter = (value) => {
    getData({
        per_page: props.additional.filters.per_page ?? 20,
        order_by: value.order_by,
        order_type: value.order_type,
        search_fields: value.search_fields,
        search_values: value.search_values,
    });
};

const getData = (params) => {
    router.get(props.additional.urlIndex, params, {
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
                <div class="dataTables_wrapper dt-bootstrap5">
                    <Datatables
                        :columns="additional.columns"
                        :pagination="additional.data"
                        :filters="additional.filters"
                        @onFilter="onFilter"
                    />

                    <DatatableFooterWrapper
                        :pagination="additional.data.meta"
                        :filters="additional.filters"
                        @onChange="changePageLength"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
