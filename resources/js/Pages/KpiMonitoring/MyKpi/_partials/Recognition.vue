<script setup>
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VListOldFileShow from "@/Shared/ProjectMonitoring/ResearchProgress//Partials/VListOldFileShow.vue";
import VResearcherInvolvedTableShow from "@/Shared/KpiMonitoring/VResearcherInvolvedTableShow.vue";

import _ from "lodash";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;
</script>

<template>
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
                label="Project"
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
        v-if="initValue.researcher_involved?.length > 0"
        title="Team Member"
        :value="initValue.researcher_involved"
    />

    <div class="row">
        <div class="col-12 mb-3">
            <VListOldFileShow :value="initValue.old_files" />
        </div>
    </div>
</template>
