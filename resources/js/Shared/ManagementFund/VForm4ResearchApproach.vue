<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VContentEditorWithLabel from "@/Shared/Form/VContentEditorWithLabel.vue";
import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VRadioWithLabel from "@/Shared/Form/VRadioWithLabel.vue";


import VActivitiesTable from "./Partials/VActivitiesTable.vue";
import VMilestonesTable from "./Partials/VMilestonesTable.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VButton from "@/Shared/Buttons/VButton.vue";

import { useForm } from "@inertiajs/vue3";
import { computed, watch } from "vue";
import { calcCompletionDate, formatMonth } from "@/Helpers/date.js";

import { useFormStore } from "@/Store/form.js";

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

const statusFactor = [
    {
        id: "done",
        description: "Done",
    },
    {
        id: "pending",
        description: "Pending",
    },
    {
        id: "cancelled",
        description: "Cancelled",
    },
];

const emits = defineEmits(["onNext", "onPrev"]);

const isLeaderInternal = computed(() => {
    return identification.value?.project_leader_type == 1;
});

const handleClickNext = () => {
    form.project_leader_type = identification.value?.project_leader_type;
    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            formStore.reset();
            emits("onNext", form.data());
        },
    });
};

const formatActivities = (activities) => {
    return activities.map((item) => {
        item.from = item.from.substring(0, 7);
        item.to = item.to.substring(0, 7);

        return item;
    });
};

const handleClickPrev = () => {
    emits("onPrev", form.data());
};

const { initValue, urlSubmit, method, proposalType } = props.additional;

const identification = computed(() => props.additional.identification);

const form = useForm({
    proposal_type: proposalType,
    project_leader_type: identification.value?.project_leader_type,
    research_methodology: initValue?.research_methodology ?? "",
    risk_factor: initValue?.risk_factor ?? "",
    risk_technical: initValue?.risk_technical ?? "",
    risk_budget: initValue?.risk_budget ?? "",
    risk_timing: initValue?.risk_timing ?? "",

    schedule_start_date: initValue?.schedule_start_date
        ? initValue.schedule_start_date.substring(0, 7)
        : "",
    schedule_duration: initValue?.schedule_duration,

    activities: initValue?.activities
        ? formatActivities(initValue.activities)
        : [],
    milestones: initValue?.milestones ?? [],

    _method: method,
});

const formStore = useFormStore();

const completionDate = computed(() => {
    const startDate = form.schedule_start_date;
    const duration = form.schedule_duration;

    return calcCompletionDate(startDate, duration);
});

watch(
    () => form.data(),
    () => {
        formStore.setIsDirty(form.isDirty);
    }
);
</script>
<template>
    <h3>Details</h3>
    <VDevider class="my-3" />
    <br/>
    <br/>

        <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Ticket ID"
                value="Auto Generated"
                :isPlainText="false"
            />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="request_date"
                label="Request Date"
                v-model:value="form.request_date"
                type="date"
                :error="form.errors?.request_date"
                :isRequired="isLeaderInternal"
            />
        </div>
    </div>

            <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="reported_by"
                label="Reported By"
                v-model:value="form.reported_by"
                type="text"
                :error="form.errors?.reported_by"
                :isRequired="isLeaderInternal"
            />
    </div>

                <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="department"
                label="Department"
                v-model:value="form.reported_by"
                type="text"
                :error="form.errors?.reported_by"
                :isRequired="isLeaderInternal"
            />
    </div>

        <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="issue_type"
                label="Issue Type"
                v-model:value="form.issue_type"
                type="text"
                :error="form.errors?.issue_type"
                :isRequired="isLeaderInternal"
            />
        </div>
    
    <br/>
    <br/>

    <h5>Description</h5>
    <VDevider class="my-3" />
    <div class="row mb-3">
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="project_description"
                v-model:value="form.project_description"
                :error="form.errors.project_description"
                :isRequired="isLeaderInternal"
            />
        </div>
    </div>
    <br/>
    <br/>

    <!-- <div class="row mb-3">
        <div class="col-12 mb-3">
            <h5>
                Project Activities
                <span v-if="isLeaderInternal" class="text-danger">*</span>
            </h5>
            <VActivitiesTable v-model:value="form.activities" />
        </div>
        <div class="col-12 mb-3">
            <h5>
                Project Milestone
                <span v-if="isLeaderInternal" class="text-danger">*</span>
            </h5>
            <VMilestonesTable v-model:value="form.milestones" />
        </div>
    </div> -->

    <h5>Priority Level</h5>
     <VDevider class="my-3" />
    <!-- <div class="row mb-3 mt-3">
        <div class="col-6">
            <VRadioWithLabel
                elId="risk_factor"
                label="Factor"
                v-model:value="form.risk_factor"
                :options="optionsFactor"
                :error="form.errors.risk_factor"
                :isRequired="isLeaderInternal"
            />
        </div>
    </div> -->
    <!-- <div class="row mb-3">
        <div class="col-6">
            <VRadioWithLabel
                elId="risk_technical"
                label="Technical Risk"
                v-model:value="form.risk_technical"
                :options="optionsFactor"
                :error="form.errors.risk_technical"
                :isRequired="isLeaderInternal"
            />
        </div>
    </div> -->
    <!-- <div class="row mb-3">
        <div class="col-6">
            <VRadioWithLabel
                elId="risk_budget"
                label="Budget Risk"
                v-model:value="form.risk_budget"
                :options="optionsFactor"
                :error="form.errors.risk_budget"
                :isRequired="isLeaderInternal"
            />
        </div>
    </div>  -->
<div class="row mb-3">
        <div class="col-6">
            <VRadioWithLabel
                elId="priority_level"
                label="Priority"
                v-model:value="form.maintenance_timing"
                :options="optionsFactor"
                :error="form.errors.maintenance_timing"
                :isRequired="isLeaderInternal"
            />
        </div>
    <div class="col-6">
            <VRadioWithLabel
                elId="status"
                label="Status"
                v-model:value="form.status"
                :options="statusFactor"
                :error="form.errors.status"
                :isRequired="isLeaderInternal"
            />
            </div>

</div>
<br/>
<br/>

    <h5>Duration of Maintenance</h5>
     <VDevider class="my-3" />
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="schedule_start_date"
                label="Starting Date"
                v-model:value="form.schedule_start_date"
                type="date"
                :error="form.errors?.schedule_start_date"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="schedule_duration"
                label="Duration"
                v-model:value="form.schedule_duration"
                type="number"
                :error="form.errors?.schedule_duration"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                elId="schedule_completion_date"
                label="Completion Date"
                :value="completionDate"
                type="month"
                :isPlainText="false"
            />
        </div>
    </div>
   
    <br/>
    <br/>


    <h5>Solution Summary</h5>
     <VDevider class="mb-4" />
        <div class="row mb-3">
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="solution_summary"
                v-model:value="form.solution_summary"
                :error="form.errors.solution_summary"
                :isRequired="isLeaderInternal"
            />
        </div>
    </div>

    <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="follow_up_required"
                label="Follow-up Required"
                v-model:value="form.follow_up_required"
                type="text"
                :error="form.errors?.follow_up_required"
                :isRequired="isLeaderInternal"
            />
    </div>

    
    <br/>
    <br/>
    
    <h5>Remarks</h5>
    <VDevider class="mb-4" />

            <div class="row mb-3">
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="solution_summary"
                v-model:value="form.remarks"
                :error="form.errors.remarks"
                :isRequired="isLeaderInternal"
            />
        </div>
    </div>

    <VDevider class="mb-4" />

    <div class="text-end">
        <VButton class="me-2" type="button" @onClick="handleClickPrev">
            Back
        </VButton>
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickNext"
            :isProcessing="form.processing"
        >
            Save
        </VButtonSubmit>
    </div>
</template>
