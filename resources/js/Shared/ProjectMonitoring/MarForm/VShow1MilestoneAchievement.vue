<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VInputReadonlyStyle2WithLabel from "@/Shared/Form/VInputReadonlyStyle2WithLabel.vue";
import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";
import VMilestonesTable from "./Partials/VMilestonesTable.vue";
import axios from "axios";
import { onMounted, ref } from "vue";
import VMilestonesTableShow from "./Partials/VMilestonesTableShow.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;

const appBaseUrl = usePage().props.appBaseUrl;

const proposal = ref({});
const milestones = ref([]);

onMounted(() => {
    populateProposal(initValue.proposal_id);
    populateMilestone(initValue.proposal_id, initValue.year, initValue.quarter);
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

const getMilestone = async (id, year, quarter) => {
    const response = await axios.get(`${appBaseUrl}/resources/milestone`, {
        params: {
            proposal_id: id,
            year: year,
            quarter: quarter,
        },
    });
    return response;
};

const populateMilestone = async (id, year, quarter) => {
    getMilestone(id, year, quarter).then((response) => {
        const { data } = response.data;
        milestones.value = data;
    });
};
</script>

<template>
    <h3>Milestone Achievement Report</h3>
    <VDevider class="my-3" />
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Project Number"
                :value="initValue.project_number"
                :isPlainText="true"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Project Title"
                :value="proposal?.project_title"
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
                :value="proposal?.researcher?.name"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyWithLabel
                elId="researcher_tel_no"
                label="Telephone"
                :value="proposal?.researcher?.tel_no"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-0">
            <VInputReadonlyWithLabel
                elId="researcher_fax_no"
                label="Fax"
                :value="proposal?.researcher?.fax_no"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyWithLabel
                elId="researcher_email"
                label="Email"
                :value="proposal?.researcher?.email"
                :isPlainText="true"
            />
        </div>
    </div>
    <h5 class="mb-3 mt-3">Quarter Report</h5>
    <div class="row">
        <div class="col-md-6 mb-3 order-md-0">
            <VInputReadonlyWithLabel
                label=""
                :value="initValue.year + ' - quarter ' + initValue.quarter"
            />
        </div>
    </div>

    <VMilestonesTableShow :value="milestones" />

    <VDevider class="mb-4" />

    <h5 class="mb-3 mt-3">Planned milestone date</h5>

    <div class="row">
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                elId="reason_not_achieved"
                label="Reasons for non-achievement"
                :value="initValue.reason_not_achieved"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                elId="corrective_action"
                label="Proposed adjustments/corrective actions"
                :value="initValue.corrective_action"
            />
        </div>

        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyStyle2WithLabel
                elId="revised_completion_date"
                label="Revised milestone completion date"
                type="month"
                :value="initValue.revised_completion_date"
            />
        </div>
    </div>

    <VDevider class="mb-4" />

    <h5 class="mb-3 mt-3">Impact on project schedule</h5>

    <div class="row">
        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyStyle2WithLabel
                elId="request_extension"
                label="Request for extension"
                type="number"
                unit="Month"
                :value="initValue.request_extension"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3 order-md-1">
            <VInputReadonlyStyle2WithLabel
                elId="new_completion_date"
                label="New date of project completion"
                type="month"
                :value="initValue.new_completion_date"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                elId="reason_for_extension"
                label="Reasons for extension"
                :value="initValue.reason_for_extension"
            />
        </div>
    </div>

    <VDevider class="mb-4" />
</template>
