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
                elId="no_germplasm"
                label="No of Germplasm"
                :value="initValue.no_germplasm"
                :widthLabel="3"
                :widthInput="9"
            />
        </div>
    </div>
</template>
