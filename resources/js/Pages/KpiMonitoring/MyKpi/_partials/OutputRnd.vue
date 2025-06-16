<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";

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
                elId="user_id"
                label="Project Leader"
                :value="initValue.kpi_achievement?.user?.name"
                :widthLabel="3"
                :widthInput="9"
            />
        </div>
        <div class="col-6 mb-3">
            <VInputReadonlyWithLabel
                elId="status"
                label="Status"
                :value="initValue.output_status.description"
                :widthLabel="3"
                :widthInput="9"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-6 mb-3">
            <VInputReadonlyWithLabel
                elId="output"
                label="Output"
                :value="initValue.output"
                :widthLabel="3"
                :widthInput="9"
            />
        </div>
        <div class="col-6 mb-3">
            <VInputReadonlyWithLabel
                elId="date_output"
                label="Date"
                :value="initValue.date_output"
                :widthLabel="3"
                :widthInput="9"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-6 mb-3">
            <VInputReadonlyWithLabel
                elId="type"
                label="Type of Output"
                :value="initValue.output_type.description"
                :widthLabel="3"
                :widthInput="9"
            />
        </div>
        <div class="col-6 mb-3">
            <VInputReadonlyWithLabel
                elId="source_project"
                label="Source of Project"
                :value="initValue.source_project"
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
</template>
