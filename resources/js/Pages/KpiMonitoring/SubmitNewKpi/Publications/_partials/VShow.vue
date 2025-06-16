<script setup>
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VInputResearcherInvolvedShow from "@/Shared/KpiMonitoring/VInputResearcherInvolvedShow.vue";
import VListOldFileShow from "@/Pages/KpiMonitoring/SubmitNewKpi/_partials/VListOldFileShow.vue";

import _ from "lodash";

const props = defineProps({
    initValue: Object,
    files: Array,
});
</script>

<template>
    <div class="row">
        <div class="col-12 mb-3">
            <VInputReadonlyWithLabel
                elId="date_published"
                label="Date"
                type="date"
                :value="initValue.date_published"
                :widthLabel="2"
                :widthInput="4"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <VInputReadonlyWithLabel
                elId="user_id"
                label="Project Leader"
                :value="initValue.kpi_achievement?.user?.name"
                :widthLabel="2"
                :widthInput="4"
            />
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <VInputResearcherInvolvedShow
                label="Co-Author"
                :value="initValue.researcher_involved"
                :widthLabel="2"
                :widthInput="7"
            />
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <VInputReadonlyWithLabel
                elId="title"
                label="Publication (APA Format)"
                :value="initValue.title"
                :widthLabel="2"
                :widthInput="10"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <VInputReadonlyWithLabel
                elId="ref_pub_type_d"
                label="Type of Publication"
                :value="initValue.type.description"
                :widthLabel="2"
                :widthInput="4"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <VInputReadonlyWithLabel
                elId="publisher"
                label="Publisher"
                :value="initValue.publisher"
                :widthLabel="2"
                :widthInput="4"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <VInputReadonlyWithLabel
                elId="project_number"
                label="Project Number"
                :value="initValue.proposal?.project_number"
                :widthLabel="2"
                :widthInput="4"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <VInputReadonlyWithLabel
                elId="project_title"
                label="Project Title"
                :value="initValue.proposal?.project_title"
                :widthLabel="2"
                :widthInput="4"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <VListOldFileShow :value="files" />
        </div>
    </div>
</template>
