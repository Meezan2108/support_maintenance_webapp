<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";

import { useForm } from "@inertiajs/vue3";

import { ref } from "vue";
import VListOldFileShow from "./_partials/VListOldFileShow.vue";

import _ from "lodash";
import VResearcherInvolvedTableShow from "../../../../Shared/KpiMonitoring/VResearcherInvolvedTableShow.vue";
import VSingleCommentShow from "../../../../Shared/KpiMonitoring/VSingleCommentShow.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, initValue, file, filters, method } = props.additional;

const selFile = ref(null);

const form = useForm({
    old_files: file,
    new_files: [],
    is_submited: false,
    _method: method,
});

const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: urlIndex,
        label: "Recognition",
    },
    {
        url: "#",
        label: "View Recognition",
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
                        View Recognition
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />

                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="date"
                            label="Date"
                            :value="initValue.date"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="recognition"
                            label="Recognition"
                            :value="initValue.recognition"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="project"
                            label="Event"
                            :value="initValue.project"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="type"
                            label="Type of Recognition"
                            :value="initValue.recognition_type"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
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
                            elId="project_number"
                            label="Project Number"
                            :value="initValue.proposal?.project_number"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="project_title"
                            label="Project Title"
                            :value="initValue.proposal?.project_title"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <VResearcherInvolvedTableShow
                    v-if="initValue.researcher_involved.length > 0"
                    title="Team Member"
                    :value="initValue.researcher_involved"
                />
                <div class="row">
                    <div class="col-12 mb-3">
                        <VListOldFileShow :value="form.old_files" />
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
