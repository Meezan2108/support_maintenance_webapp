<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTimelineActivities from "@/Shared/ManagementFund/Partials/VTimelineActivities.vue";

import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { generateArrYear, calcCompletionDate } from "@/Helpers/date.js";

const user = computed(() => usePage().props.authUser);

const props = defineProps({
    additional: Object,
});

const { initValue, method, urlSubmit, proposalType } = props.additional;

const form = useForm({
    ptj_code: initValue.ptj_code ?? [],
    project_number: initValue.project_number,

    schedule_start_date: initValue?.schedule_start_date
        ? initValue.schedule_start_date.substring(0, 7)
        : "",
    schedule_duration: initValue?.schedule_duration,

    _method: method,
});

const arrYear = computed(() => {
    let startDate = initValue?.schedule_start_date;
    let duration = initValue?.schedule_duration;

    return generateArrYear(startDate, duration);
});

const completionDate = computed(() => {
    let startDate = form.schedule_start_date;
    let duration = form.schedule_duration;

    return calcCompletionDate(startDate, duration);
});

const activities = computed(() => initValue?.activities);
const milestones = computed(() => initValue?.milestones);

const emits = defineEmits(["onNext"]);

const handleClickNext = () => {
    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            emits("onNext", form.data());
        },
    });
};
</script>
<template>
    <h3>Project Timeline</h3>
    <VDevider class="my-3" />

    <div class="row">
        <div class="col-md-6 mb-3 order-md-0">
            <VInputReadonlyWithLabel
                elId="ptj_code"
                label="PTJ Code"
                :value="form.ptj_code?.join(', ')"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyWithLabel
                elId="project_number"
                label="Project Number"
                :value="form.project_number"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-0">
            <VInputReadonlyWithLabel
                elId="project_title"
                label="Project Title"
                :value="initValue.project_title"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyWithLabel
                label="Application ID"
                :value="initValue.application_id"
                :isPlainText="true"
            />
        </div>
    </div>

    <h5 class="mb-3 mt-3">Project's Leader Information</h5>
    <div class="row">
        <div class="col-md-6 mb-3 order-md-0">
            <VInputReadonlyWithLabel
                elId="researcher_name"
                label="Project Leader"
                :value="initValue.researcher.name"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyWithLabel
                elId="researcher_tel_no"
                label="Telephone"
                :value="initValue.researcher.tel_no"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-0">
            <VInputReadonlyWithLabel
                elId="researcher_fax_no"
                label="Fax"
                :value="initValue.researcher.fax_no"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyWithLabel
                elId="researcher_email"
                label="Email"
                :value="initValue.researcher.email"
                :isPlainText="true"
            />
        </div>
    </div>
    <VDevider class="mb-4" />

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

    <h5>Completion Date</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                elId="schedule_start_date"
                label="Starting Date"
                :value="form.schedule_start_date"
                :isPlainText="true"
            />
        </div>
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                elId="schedule_duration"
                label="Duration"
                :value="form.schedule_duration"
                :isPlainText="true"
            />
        </div>
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                elId="schedule_completion_date"
                label="Completion Date"
                :value="completionDate"
                :isPlainText="true"
            />
        </div>
    </div>
</template>
