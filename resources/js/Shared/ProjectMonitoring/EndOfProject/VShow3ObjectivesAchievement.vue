<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VObjectivesTableShow from "./Partials/VObjectivesTableShow.vue";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;
</script>

<template>
    <h3>Objectives Achievement</h3>
    <VDevider class="my-3" />
    <div class="col-12 mb-3">
        <VObjectivesTableShow
            label="Original Project Objectives"
            subLabel="(Please state the specific project objectives as described in Section II of the Application Form)"
            :value="initValue?.original_objectives ?? []"
        />
    </div>
    <div class="col-12 mb-3">
        <VObjectivesTableShow
            elId="objectives_achieved"
            label="Objectives Achieved"
            subLabel="(Please state the extent to which the project objectives were achieved)"
            :value="initValue.objectives_achieved ?? []"
        />
    </div>
    <div class="col-12 mb-3">
        <VObjectivesTableShow
            elId="objectives_not_achieved"
            label="Objectives Not Achieved"
            subLabel="(Please identify the objectives that were not achieved and give reasons)"
            :value="initValue.objectives_not_achieved ?? []"
        />
    </div>
</template>
