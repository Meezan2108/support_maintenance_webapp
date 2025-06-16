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
                elId="date"
                label="Date"
                :value="initValue.date"
                :widthLabel="3"
                :widthInput="9"
            />
        </div>
        <div class="col-6 mb-3">
            <VInputReadonlyWithLabel
                elId="output"
                label="Output"
                :value="initValue.output"
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
        <div class="col-6 mb-3">
            <VInputReadonlyWithLabel
                elId="ref_patent_id"
                label="Type of IPRs"
                :value="initValue.patent.description"
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
