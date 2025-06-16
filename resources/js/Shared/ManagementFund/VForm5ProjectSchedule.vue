<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VTimelineActivities from "./Partials/VTimelineActivities.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VButton from "@/Shared/Buttons/VButton.vue";

import { generateArrYear } from "@/Helpers/date.js";

import { computed } from "vue";

const props = defineProps({
    additional: Object,
});

const arrYear = computed(() => {
    let startDate = props.additional.researchApproach?.schedule_start_date;
    let duration = props.additional.researchApproach?.schedule_duration;

    return generateArrYear(startDate, duration);
});

const activities = computed(
    () => props.additional.researchApproach?.activities
);

const milestones = computed(() =>
    props.additional.researchApproach?.milestones.map((item) => {
        return {
            activities: item.activities,
            from: item.from.substr(0, 7),
            to: item.from.substr(0, 7),
        };
    })
);

const emits = defineEmits(["onNext", "onPrev"]);

const handleClickNext = () => {
    emits("onNext");
};

const handleClickPrev = () => {
    emits("onPrev");
};
</script>
<template>
    <h3>Project Schedule</h3>
    <VDevider class="my-3" />

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <VTimelineActivities
                title="Activities"
                :arrYear="arrYear"
                :activities="activities"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <VTimelineActivities
                title="Milestones"
                :arrYear="arrYear"
                :activities="milestones"
            />
        </div>
    </div>

    <VDevider class="mb-4" />
    <div class="text-end">
        <VButton class="me-2" type="button" @onClick="handleClickPrev">
            Back
        </VButton>
        <VButtonSubmit type="button" @onCLickSubmit="handleClickNext">
            Save
        </VButtonSubmit>
    </div>
</template>

<style scoped>
.fixed-column {
    position: sticky;
    left: 0;
    z-index: 1;
    background-color: white;
}
</style>
