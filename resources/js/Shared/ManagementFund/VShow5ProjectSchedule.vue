<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VTimelineActivities from "./Partials/VTimelineActivities.vue";

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

const isInRange = (start, end, year, month) => {
    var dateToCheck = new Date(
        year + "-" + String(month).padStart(2, "0") + "-01"
    );
    var startDate = new Date(start + "-01");
    var endDate = new Date(end + "-01");

    return dateToCheck >= startDate && dateToCheck <= endDate;
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
                title="Milestone"
                :arrYear="arrYear"
                :activities="milestones"
            />
        </div>
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
