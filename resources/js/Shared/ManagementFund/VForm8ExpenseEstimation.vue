<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VExpensesTable from "./Partials/VExpensesTable.vue";
import { computed, watch } from "vue";
import { generateArrYear } from "@/Helpers/date.js";

import { useFormStore } from "@/Store/form.js";

const props = defineProps({
    additional: Object,
});

const { initValue, method, urlSubmit, proposalType } = props.additional;

const identification = computed(() => props.additional.identification);

const years = computed(() => {
    let startDate = props.additional.researchApproach?.schedule_start_date;
    let duration = props.additional.researchApproach?.schedule_duration;

    return generateArrYear(startDate, duration);
});

const form = useForm({
    years: years,
    proposal_type: proposalType,
    project_leader_type: identification.value?.project_leader_type,
    V11000: initValue?.V11000 ?? [],
    V21000: initValue?.V21000 ?? [],
    V26000: initValue?.V26000 ?? [],
    V28000: initValue?.V28000 ?? [],
    V29000: initValue?.V29000 ?? [],
    _method: method,
});

const formStore = useFormStore();

const emits = defineEmits(["onNext", "onPrev"]);

const handleClickNext = () => {
    form.years = years;
    form.project_leader_type = identification.value?.project_leader_type;
    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            formStore.reset();
            emits("onNext", form.data());
        },
    });
};

const handleClickPrev = () => {
    emits("onPrev", form.data());
};

watch(
    () => form.data(),
    () => {
        formStore.setIsDirty(form.isDirty);
    }
);
</script>
<template>
    <h3>Direct Expenses Estimation</h3>
    <VDevider class="my-3" />

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Travel & Transportation (V21000)</h6>
            <VExpensesTable
                v-model:value="form.V21000"
                title="Travel & Transportation (V21000)"
                :years="years"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Research Materials & Supplies (V26000)</h6>
            <VExpensesTable
                v-model:value="form.V26000"
                title="Researach Materials & Supplies (V26000)"
                :years="years"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Minor Modifications & Repairs (V28000)</h6>
            <VExpensesTable
                v-model:value="form.V28000"
                title="Minor Modifications & Repairs (V28000)"
                :years="years"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Special Services (V29000)</h6>
            <VExpensesTable
                v-model:value="form.V29000"
                title="Special Services (V29000)"
                :years="years"
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
