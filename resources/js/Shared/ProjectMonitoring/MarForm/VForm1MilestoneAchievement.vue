<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VInputStyle2WithLabel from "@/Shared/Form/VInputStyle2WithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";
import VContentEditorWithLabel from "@/Shared/Form/VContentEditorWithLabel.vue";
import VMilestonesTable from "./Partials/VMilestonesTable.vue";

import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";
import axios from "axios";
import { generateArrYearQuarter } from "@/Helpers/date.js";

const props = defineProps({
    additional: Object,
});

const { initValue, projectNumbers, urlSubmit, formType, method } =
    props.additional;

const proposal = ref({});

const form = useForm({
    proposal_id: initValue?.proposal_id,
    year_quarter: initValue?.year_quarter,
    year: initValue?.year,
    quarter: initValue?.quarter,
    milestones: initValue?.milestones,

    reason_not_achieved: initValue?.reason_not_achieved,
    corrective_action: initValue?.corrective_action,
    revised_completion_date: initValue?.revised_completion_date,
    request_extension: initValue?.request_extension,
    new_completion_date: initValue?.new_completion_date,
    reason_for_extension: initValue?.reason_for_extension,

    _method: method,
});

onMounted(() => {
    if (initValue?.proposal_id) {
        populateProposal(initValue.proposal_id);
    }
    if (initValue?.year_quarter) {
        populateMilestone(
            initValue.proposal_id,
            initValue.year,
            initValue.quarter
        );
    }
});

const arrYearQuarters = computed(() => {
    let startDate = proposal.value.schedule_start_date;
    let duration = proposal.value.schedule_duration;

    let data = generateArrYearQuarter(startDate?.substr(0, 7), duration);

    if (formType == "edit") {
        return data.filter((item) => item.id == form.year_quarter);
    }

    return data;
});

const appBaseUrl = usePage().props.appBaseUrl;

const emits = defineEmits(["onNext"]);

const handleClickNext = async () => {
    if (form.year_quarter) {
        const [year, quarter] = form.year_quarter.split("-");
        form.year = year ?? "";
        form.quarter = quarter ?? "";
    }

    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            emits("onNext", form.data());
        },
    });
};

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

const getReport = async (id, year, quarter) => {
    const response = await axios.get(
        `${appBaseUrl}/resources/report-milestone`,
        {
            params: {
                proposal_id: id,
                year: year,
                quarter: quarter,
            },
        }
    );
    return response;
};

const getMilestone = async (id, year, quarter) => {
    const response = await axios.get(`${appBaseUrl}/resources/milestone`, {
        params: {
            proposal_id: id,
        },
    });
    return response;
};

const populateMilestone = async (id, year, quarter) => {
    getMilestone(id, year, quarter).then((response) => {
        const { data } = response.data;
        form.milestones = data;
    });
};

watch(
    () => form.proposal_id,
    (newValue) => {
        populateProposal(newValue);
    }
);

watch(
    () => form.year_quarter,
    (newValue) => {
        const [year, quarter] = newValue.split("-");
        populateMilestone(form.proposal_id, year, quarter);
    }
);
</script>

<template>
    <h3>Milestone Achievement Report</h3>
    <VDevider class="my-3" />
    <div class="row">
        <div class="col-md-6 mb-3">
            <VSelectWithLabel
                elId="proposal_id"
                label="Project Number"
                v-model:value="form.proposal_id"
                :options="projectNumbers"
                :error="form.errors?.proposal_id"
                :isRequired="true"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Project Title"
                :value="proposal?.project_title"
                :isPlainText="false"
            />
        </div>
    </div>

    <h5 class="mb-3 mt-3">Project's Leader Information</h5>
    <div class="row">
        <div class="col-md-6 mb-3 order-md-0">
            <VInputReadonlyWithLabel
                elId="researcher_name"
                label="Project Leader"
                :value="proposal?.researcher?.name"
                :isPlainText="false"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyWithLabel
                elId="researcher_tel_no"
                label="Telephone"
                :value="proposal?.researcher?.tel_no"
                :isPlainText="false"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-0">
            <VInputReadonlyWithLabel
                elId="researcher_fax_no"
                label="Fax"
                :value="proposal?.researcher?.fax_no"
                :isPlainText="false"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyWithLabel
                elId="researcher_email"
                label="Email"
                :value="proposal?.researcher?.email"
                :isPlainText="false"
            />
        </div>
    </div>
    <h5 class="mb-3 mt-3">Quarter Report</h5>
    <div class="row">
        <div class="col-md-6 mb-3 order-md-0">
            <VSelectWithLabel
                elId="yeaer_quarter"
                label=""
                v-model:value="form.year_quarter"
                :options="arrYearQuarters"
                :error="form.errors?.year_quarter"
                :isRequired="true"
            />
        </div>
    </div>

    <VMilestonesTable v-model:value="form.milestones" />

    <VDevider class="mb-4" />

    <h5 class="mb-3 mt-3">Planned milestone date</h5>

    <div class="row">
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="reason_not_achieved"
                label="Reasons for non-achievement"
                v-model:value="form.reason_not_achieved"
                :error="form.errors.reason_not_achieved"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="corrective_action"
                label="Proposed adjustments/corrective actions"
                v-model:value="form.corrective_action"
                :error="form.errors.corrective_action"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-1">
            <VInputStyle2WithLabel
                elId="revised_completion_date"
                label="Revised milestone completion date"
                type="month"
                v-model:value="form.revised_completion_date"
                :error="form.errors.revised_completion_date"
            />
        </div>
    </div>

    <VDevider class="mb-4" />

    <h5 class="mb-3 mt-3">Impact on project schedule</h5>

    <div class="row">
        <div class="col-md-6 mb-3 order-md-1">
            <VInputStyle2WithLabel
                elId="request_extension"
                label="Request for extension"
                type="number"
                unit="Month"
                v-model:value="form.request_extension"
                :error="form.errors.request_extension"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3 order-md-1">
            <VInputStyle2WithLabel
                elId="new_completion_date"
                label="New date of project completion"
                type="month"
                v-model:value="form.new_completion_date"
                :error="form.errors.new_completion_date"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="reason_for_extension"
                label="Reasons for extension"
                v-model:value="form.reason_for_extension"
                :error="form.errors.reason_for_extension"
            />
        </div>
    </div>

    <VDevider class="mb-4" />

    <div class="text-end">
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickNext"
            :isProcessing="form.processing"
        >
            Save
        </VButtonSubmit>
    </div>
</template>
