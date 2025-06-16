<script setup>
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";

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
                :value="initValue.quarter ? 'Quarter ' + initValue.quarter : ''"
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
</template>
