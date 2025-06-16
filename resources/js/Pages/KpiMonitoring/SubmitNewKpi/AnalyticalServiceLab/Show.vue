<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";

import _ from "lodash";
import VSingleCommentShow from "../../../../Shared/KpiMonitoring/VSingleCommentShow.vue";
import VListOldFileShow from "@/Pages/KpiMonitoring/SubmitNewKpi/_partials/VListOldFileShow.vue";

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
        label: "Analyitical Service Lab",
    },
    {
        url: "#",
        label: "View Analytical Service Lab",
    },
];
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
                        View Analytical Service Lab
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />

                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="user_id"
                            label="Project Leader"
                            :value="initValue.kpi_achievement?.user?.name"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="year"
                            label="Year"
                            :value="initValue.year"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="quarter"
                            label="Quarterly"
                            :value="
                                initValue.quarter
                                    ? 'Quarter ' + initValue.quarter
                                    : ''
                            "
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="no_analysis"
                            label="No of Analysis"
                            :value="initValue.no_analysis"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="no_sample"
                            label="No of Sample"
                            :value="initValue.no_sample"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="no_analysis_protocol"
                            label="No of Analysis Protocol"
                            :value="initValue.no_analysis_protocol"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <VListOldFileShow :value="file" />
                    </div>
                </div>

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
