<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VRadioReadonlyWithLabel from "@/Shared/Form/VRadioReadonlyWithLabel.vue";

import VActivitiesTableShow from "./Partials/VActivitiesTableShow.vue";
import VMilestonesTableShow from "./Partials/VMilestonesTableShow.vue";

import { computed } from "vue";
import { calcCompletionDate } from "@/Helpers/date.js";

const props = defineProps({
    additional: Object,
});

const optionsFactor = [
    {
        id: "low",
        description: "Low",
    },
    {
        id: "medium",
        description: "Medium",
    },
    {
        id: "high",
        description: "High",
    },
];

const { initValue } = props.additional;

const completionDate = computed(() => {
    const startDate = initValue.schedule_start_date;
    const duration = initValue.schedule_duration;

    return calcCompletionDate(startDate, duration);
});

const formatScheduleStart = computed(() => {
    if (!initValue.schedule_start_date) return "";

    let d = new Date(initValue.schedule_start_date + "-01");
    return d.toLocaleString("default", { month: "long", year: "numeric" });
});
</script>
<template>
    <h3>Research Approach</h3>
    <VDevider class="my-3" />

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                label="Research Methodology"
                :value="initValue.research_methodology"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h5>Project Activities</h5>
            <VActivitiesTableShow :value="initValue.activities" />
        </div>
        <div class="col-12 mb-3">
            <h5>Project Milestone</h5>
            <VMilestonesTableShow :value="initValue.milestones" />
        </div>
    </div>

    <h5>Risk of the Project</h5>
    <div class="row mb-3 mt-3">
        <div class="col-6">
            <VRadioReadonlyWithLabel
                elId="risk_factor"
                label="Factor"
                :value="initValue.risk_factor"
                :options="optionsFactor"
            />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <VRadioReadonlyWithLabel
                elId="risk_technical"
                label="Technical Risk"
                :value="initValue.risk_technical"
                :options="optionsFactor"
            />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <VRadioReadonlyWithLabel
                elId="risk_budget"
                label="Budget Risk"
                :value="initValue.risk_budget"
                :options="optionsFactor"
            />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <VRadioReadonlyWithLabel
                elId="risk_timing"
                label="Timing Risk"
                :value="initValue.risk_timing"
                :options="optionsFactor"
            />
        </div>
    </div>

    <h5>Completion Date</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Starting Date"
                :value="formatScheduleStart"
            />
        </div>
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Duration"
                :value="initValue.schedule_duration + ' months'"
            />
        </div>
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Completion Date"
                :value="completionDate"
            />
        </div>
    </div>
</template>
