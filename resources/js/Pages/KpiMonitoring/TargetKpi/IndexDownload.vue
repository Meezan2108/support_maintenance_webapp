<script setup>
import { Head, router } from "@inertiajs/vue3";

import Datatables from "@/Shared/Tables/Datatables.vue";
import DatatableFooterWrapper from "@/Shared/Tables/DatatableFooterWrapper.vue";

import VAlert from "@/Shared/VAlert.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel";
import { computed, ref, watch } from "vue";
import VButton from "../../../Shared/Buttons/VButton.vue";

let props = defineProps({
    title: String,
    additional: Object,
});

const { columns, isResearcher, years, urlDownload, urlIndex } =
    props.additional;

const list = computed(() => props.additional.list);
const filters = computed(() => props.additional.filters);
const year = ref();

const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: urlIndex,
        label: "Agency KPI Target",
    },
    {
        url: "#",
        label: "Download",
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

const downloadYear = () => {
    const selYear = year.value;

    if (!selYear) return false;

    router.visit(urlDownload + "/" + selYear);
};

watch(year, (newValue) => {
    getList({
        search_fields: ["year"],
        search_values: [newValue],
    });
});
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
                <div v-if="!isResearcher" class="row mb-3">
                    <div class="col-md-6">
                        <VSelectWithLabel
                            elId="year"
                            label="Report at Year"
                            v-model:value="year"
                            :options="years"
                        />
                    </div>
                    <div class="col-md-6">
                        <VButton btnStyle="btn-primary" @click="downloadYear">
                            Download
                        </VButton>
                    </div>
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
