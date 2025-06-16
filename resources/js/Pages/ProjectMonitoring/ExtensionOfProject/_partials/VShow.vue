<script setup>
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTimelineActivitiesEop from "@/Shared/ProjectMonitoring/ExtensionProject/Partials/VTimelineActivities.vue";
import VMilestonesShowTable from "@/Shared/ProjectMonitoring/ExtensionProject/Partials/VMilestonesShowTable.vue";

import { calcCompletionDate, generateArrYear } from "@/Helpers/date.js";
import { getIntValue, formatNumber } from "@/Helpers/number.js";

import { computed, onMounted, ref } from "vue";

const props = defineProps({
    proposal: Object,
    initValue: Object,
    otherMilestones: Array,
});

const { proposal, initValue } = props;
const granttchart = ref([]);

onMounted(() => {
    if (initValue?.granttchart) {
        granttchart.value = initValue?.granttchart.map((item) => ({
            id: item.id,
            activities: item.description,
            from: item.from.substr(0, 7),
            to: item.from.substr(0, 7),
        }));
    }
});

const approvedExtensionMilestone = computed(() => {
    return props.otherMilestones
        .map((item) => item.granttchart)
        .flat()
        .map((item) => ({
            activities: item.description,
            from: item.from.substr(0, 7),
            to: item.from.substr(0, 7),
        }));
});

const sumApprovedExtensionDuration = computed(() => {
    return props.otherMilestones.reduce(
        (accumulator, currentValue) => accumulator + currentValue.duration,
        0
    );
});

const arrYear = computed(() => {
    let startDate = proposal.schedule_start_date;
    let duration = proposal.schedule_duration;
    let approvedDuration = getIntValue(sumApprovedExtensionDuration.value) ?? 0;

    return generateArrYear(startDate, duration + approvedDuration);
});

const newArrYear = computed(() => {
    let startDate = proposal.schedule_start_date;
    let duration = proposal.schedule_duration;
    let approvedDuration = getIntValue(sumApprovedExtensionDuration.value) ?? 0;
    let addDuration = getIntValue(initValue.duration);

    return generateArrYear(
        startDate,
        duration + approvedDuration + addDuration
    );
});

const completionDate = computed(() => {
    let startDate = proposal.schedule_start_date;
    let duration = proposal.schedule_duration;
    let approvedDuration = getIntValue(sumApprovedExtensionDuration.value) ?? 0;

    return calcCompletionDate(startDate, duration + approvedDuration);
});

const newCompletionDate = computed(() => {
    let startDate = proposal.schedule_start_date;
    let duration = proposal.schedule_duration;
    let approvedDuration = getIntValue(sumApprovedExtensionDuration.value) ?? 0;
    let addDuration = getIntValue(initValue.duration);

    return calcCompletionDate(
        startDate,
        duration + addDuration + approvedDuration
    );
});

const proposalMilestones = computed(() => {
    return proposal.milestones.map((item) => ({
        activities: item.activities,
        from: item.from.substr(0, 7),
        to: item.from.substr(0, 7),
    }));
});
</script>

<template>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        elId="project_number"
                        label="Project Number"
                        :value="proposal.project_number"
                        :isPlainText="false"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="Project Title"
                        :value="proposal?.project_title"
                        :isPlainText="false"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="researcher_name"
                        label="Project Leader"
                        :value="proposal?.researcher?.name"
                        :isPlainText="false"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="justification"
                        label="Justification"
                        :value="initValue.justification"
                        :isPlainText="false"
                    />
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="new_fund"
                        label="New Fund (if applicable)"
                        :value="initValue.new_fund"
                        :isPlainText="false"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="date_end_extension"
                        label="Actual Completion Date"
                        type="month"
                        :value="completionDate"
                        :isPlainText="false"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="duration"
                        label="Extension Duration"
                        type="number"
                        unit="Month"
                        :value="initValue.duration"
                        :isPlainText="false"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="date_end_extension"
                        label="New End Project Date"
                        :value="newCompletionDate"
                        :isPlainText="false"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="balance_to_date"
                        type="number"
                        label="Balance to date"
                        unit="RM"
                        :value="formatNumber(initValue.balance_to_date)"
                        :isPlainText="false"
                    />
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4 mt-3">
        <h6>Approved Milestone</h6>
        <VTimelineActivitiesEop
            title="Milestone"
            :arrYear="arrYear"
            :activities="proposalMilestones"
            :addActivities="approvedExtensionMilestone"
        />
    </div>

    <div class="mb-4">
        <h6>Key Milestone</h6>
        <VMilestonesShowTable
            :readonlyValue="[
                ...proposalMilestones,
                ...approvedExtensionMilestone,
            ]"
            :value="granttchart"
        />
    </div>

    <div class="mb-4 mt-3">
        <h6>New Milestone</h6>
        <VTimelineActivitiesEop
            title="Milestone"
            :arrYear="newArrYear"
            :activities="[...proposalMilestones, ...approvedExtensionMilestone]"
            :addActivities="granttchart"
        />
    </div>
</template>
