<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";
import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTimelineActivitiesEop from "@/Shared/ProjectMonitoring/ExtensionProject/Partials/VTimelineActivities.vue";
import VMilestonesTable from "@/Shared/ProjectMonitoring/ExtensionProject/Partials/VMilestonesTable.vue";

import { generateArrYear, calcCompletionDate } from "@/Helpers/date.js";
import { getIntValue } from "@/Helpers/number.js";

import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

import MilestoneService from "@/Services/Resources/MilestoneService.js";
import ExtensionMilestoneService from "@/Services/Resources/ExtensionMilestoneService.js";
import _ from "lodash";

const props = defineProps({
    initValue: Object,
    projectNumbers: Array,
    formType: String,
    method: String,
    urlSubmit: String,
    approvedDuration: Number,
});

const proposal = ref({});
const sumApprovedExtensionDuration = ref(0);
const approvedExtensionMilestone = ref([]);

const appBaseUrl = usePage().props.appBaseUrl;

const form = useForm({
    proposal_id: props.initValue?.proposal_id,
    justification: props.initValue?.justification,
    new_fund: props.initValue?.new_fund,
    duration: props.initValue?.duration,
    date_end_extension: props.initValue?.date_end_extension,
    balance_to_date: props.initValue?.balance_to_date,
    milestones: props.initValue?.milestones ?? [],
    milestones_extension: props.initValue?.granttchart ?? [],
    is_submited: 0,
    _method: props.method,
});

const arrYear = computed(() => {
    let startDate = proposal.value.schedule_start_date;
    let duration = proposal.value.schedule_duration;
    let approvedDuration = getIntValue(sumApprovedExtensionDuration.value) ?? 0;

    return generateArrYear(startDate, duration + approvedDuration);
});

const newArrYear = computed(() => {
    let startDate = proposal.value.schedule_start_date;
    let duration = proposal.value.schedule_duration;
    let approvedDuration = getIntValue(sumApprovedExtensionDuration.value) ?? 0;
    let addDuration = getIntValue(form.duration);

    return generateArrYear(
        startDate,
        duration + approvedDuration + addDuration
    );
});

const completionDate = computed(() => {
    let startDate = proposal.value.schedule_start_date;
    let duration = proposal.value.schedule_duration;
    let approvedDuration = getIntValue(sumApprovedExtensionDuration.value) ?? 0;

    return calcCompletionDate(startDate, duration + approvedDuration);
});

const newCompletionDate = computed(() => {
    let startDate = proposal.value.schedule_start_date;
    let duration = proposal.value.schedule_duration;
    let approvedDuration = getIntValue(sumApprovedExtensionDuration.value) ?? 0;
    let addDuration = getIntValue(form.duration);

    return calcCompletionDate(
        startDate,
        duration + approvedDuration + addDuration
    );
});

const listApprovedMilestone = computed(() => {
    if (_.isEmpty(approvedExtensionMilestone.value)) return [];

    return approvedExtensionMilestone.value.map((item) => ({
        activities: item.description,
        from: item.from.substr(0, 7),
        to: item.from.substr(0, 7),
    }));
});

onMounted(() => {
    if (props.initValue?.proposal_id) {
        populateProposal(props.initValue.proposal_id);
        populateMilestone(props.initValue.proposal_id);
        populateApproveExtension(props.initValue.proposal_id);
    }

    if (props.initValue?.granttchart) {
        form.milestones_extension = props.initValue?.granttchart.map(
            (item) => ({
                id: item.id,
                activities: item.description,
                from: item.from.substr(0, 10),
                to: item.from.substr(0, 10),
            })
        );
    }
});

const getProposal = async (id) => {
    const response = await axios.get(`${appBaseUrl}/resources/proposal/${id}`);
    return response;
};

const populateProposal = (id) => {
    getProposal(id).then((response) => {
        const { data } = response.data;
        proposal.value = data;
    });
};

const populateMilestone = async (id) => {
    const response = await MilestoneService.list({
        proposal_id: id,
        type: "proposal",
    });

    const { data } = response.data;

    form.milestones = data.map((item) => {
        return {
            activities: item.activities,
            from: item.from.substr(0, 7),
            to: item.from.substr(0, 7),
        };
    });
};

const populateApproveExtension = async (id) => {
    const response = await ExtensionMilestoneService.show({
        proposal_id: id,
    });

    const { data } = response.data;

    const filterExtensionMilestone = data.applications.filter(
        (item) => item.id != props.initValue?.id
    );

    sumApprovedExtensionDuration.value = filterExtensionMilestone.reduce(
        (accumulator, currentValue) => accumulator + currentValue.duration,
        0
    );

    approvedExtensionMilestone.value = filterExtensionMilestone
        .map((item) => item.granttchart)
        .flat();
};

const handleClickSubmit = async () => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Submit Report!",
    });

    form.is_submited = 0;
    if (result.isConfirmed) {
        form.is_submited = 1;
    }

    form.post(props.urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            useTaskStore().checkCount();
            useNotificationStore().reloadCount();
        },
    });
};

watch(
    () => form.proposal_id,
    (newValue) => {
        form.milestones_extension = [];
        populateProposal(newValue);
        populateMilestone(newValue);
        populateApproveExtension(newValue);
    }
);
</script>

<template>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3">
                    <VSelectWithLabel
                        elId="proposal_id"
                        label="Project Number"
                        v-model:value="form.proposal_id"
                        :options="projectNumbers"
                        :error="form.errors?.proposal_id"
                        :isRequired="true"
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
                    <VInputWithLabel
                        elId="justification"
                        label="Justification"
                        v-model:value="form.justification"
                        :error="form.errors?.justification"
                    />
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3 order-md-0">
                    <VInputWithLabel
                        elId="new_fund"
                        label="New Fund (if applicable)"
                        v-model:value="form.new_fund"
                        :error="form.errors?.new_fund"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="actual_completion_date"
                        label="Actual Completion Date"
                        type="month"
                        :value="completionDate"
                        :isPlainText="false"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputWithLabel
                        elId="duration"
                        label="Extension Duration"
                        type="number"
                        unit="Month"
                        v-model:value="form.duration"
                        :error="form.errors?.duration"
                        :isRequired="true"
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
                    <VInputWithLabel
                        elId="balance_to_date"
                        type="number"
                        label="Balance to date"
                        unit="RM"
                        v-model:value="form.balance_to_date"
                        :error="form.errors?.balance_to_date"
                    />
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4 mt-3">
        <h6>Approved Milestone</h6>
        <VTimelineActivitiesEop
            v-if="proposal"
            title="Milestone"
            :arrYear="arrYear"
            :activities="form.milestones"
            :addActivities="listApprovedMilestone"
        />
    </div>

    <div class="mb-4">
        <h6>Key Milestone</h6>
        <VMilestonesTable
            v-if="proposal"
            :readonlyValue="[...form.milestones, ...listApprovedMilestone]"
            v-model:value="form.milestones_extension"
        />
    </div>

    <div class="mb-4 mt-3">
        <h6>New Milestone</h6>
        <VTimelineActivitiesEop
            v-if="proposal"
            title="Milestone"
            :arrYear="newArrYear"
            :activities="[...form.milestones, ...listApprovedMilestone]"
            :addActivities="
                form.milestones_extension.map((item) => ({
                    activities: item.activities,
                    from: item.from.substr(0, 7),
                    to: item.from.substr(0, 7),
                }))
            "
        />
    </div>

    <VDevider class="mb-4" />

    <div class="text-end">
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickSubmit"
            :isProcessing="form.processing"
        >
            Submit
        </VButtonSubmit>
    </div>
</template>
