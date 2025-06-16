<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";

import _ from "lodash";
import VShow from "./_partials/VShow.vue";
import { listStatus } from "@/Config/approvement";
import VSingleCommentShow from "../../../../Shared/KpiMonitoring/VSingleCommentShow.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, initValue, file, filters } = props.additional;

const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: urlIndex,
        label: "Publications",
    },
    {
        url: "#",
        label: "View Publication",
    },
];

const formatStatus = (status) => {
    const objStatus = listStatus.find((item) => item.id == status);
    return objStatus?.description ?? " - ";
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
                        View Publication
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />

                <VShow :initValue="initValue" :files="file" />

                <VDevider class="mb-4" />

                <div id="comments">
                    <div class="underline-header mt-2 mb-3">
                        <h5>Comments</h5>
                    </div>

                    <VSingleCommentShow :value="initValue.kpi_achievement" />
                </div>
            </div>
        </div>
    </div>
</template>
