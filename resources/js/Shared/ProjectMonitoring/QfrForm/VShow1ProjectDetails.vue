<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";

import { formatMonth, calcCompletionDate } from "@/Helpers/date.js";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";
import axios from "axios";

const props = defineProps({
    additional: Object,
});

const { initValue, projectNumbers, method } = props.additional;

const proposal = ref({});

const form = useForm({
    proposal_id: initValue?.proposal_id,
    _method: method,
});

onMounted(() => {
    if (initValue?.proposal_id) {
        populateProposal(initValue.proposal_id);
    }
});

const emits = defineEmits(["onNext"]);

const appBaseUrl = usePage().props.appBaseUrl;

const handleClickNext = async () => {
    emits("onNext", form.data());
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

watch(
    () => form.proposal_id,
    (newValue) => {
        populateProposal(newValue);
    }
);

const completionDate = computed(() => {
    if (
        !proposal.value.schedule_start_date ||
        !proposal?.value.schedule_duration
    ) {
        return "";
    }
    return calcCompletionDate(
        proposal.value.schedule_start_date,
        proposal.value.schedule_duration
    );
});
</script>

<template>
    <h3>Project Details</h3>
    <VDevider class="my-3" />
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Project Number"
                :value="proposal?.project_number"
                :isPlainText="false"
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

    <h5 class="mb-3 mt-3">Project Duration</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                elId="schedule_duration"
                label="Duration"
                v-model:value="proposal.schedule_duration"
                type="number"
                :isPlainText="false"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                elId="schedule_start_date"
                label="Starting Date"
                :value="formatMonth(proposal.schedule_start_date)"
                :isPlainText="false"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                elId="schedule_completion_date"
                label="Completion Date"
                :value="completionDate"
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
</template>
