<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";
import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VButton from "@/Shared/Buttons/VButton.vue";

import VBenefitsTable from "./Partials/VBenefitsTable.vue";
import { computed, watch } from "vue";
import VEconomicContributionTable from "./Partials/VEconomicContributionTable.vue";

import { useFormStore } from "@/Store/form.js";

const props = defineProps({
    additional: Object,
});

const benefitsOutput = props.additional.refBenefits.filter(
    (item) => item.category == 1
);
const benefitsHuman = props.additional.refBenefits.filter(
    (item) => item.category == 2
);

const { initValue, urlSubmit, method, proposalType } = props.additional;

const identification = computed(() => props.additional.identification);

const form = useForm({
    proposal_type: proposalType,
    project_leader_type: identification.value?.project_leader_type,
    economic_contributions: initValue?.economic_contributions ?? [],
    output_expected: initValue?.output_expected ?? [],
    human_capital: initValue?.human_capital ?? [],

    _method: method,
});

const formStore = useFormStore();

const emits = defineEmits(["onNext", "onPrev"]);

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

const handleClickPrev = () => {
    emits("onPrev", form.value);
};

watch(
    () => form.data(),
    () => {
        formStore.setIsDirty(form.isDirty);
    }
);
</script>
<template>
    <h3>Benefits</h3>
    <VDevider class="my-3" />

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Output Expected from the Project</h6>
            <VBenefitsTable
                :benefits="benefitsOutput"
                v-model:value="form.output_expected"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Human Capital and Expert Development</h6>
            <VBenefitsTable
                :benefits="benefitsHuman"
                detailAs="Specialisation Area (Specific Area)"
                v-model:value="form.human_capital"
            />
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <VEconomicContributionTable
                v-model:value="form.economic_contributions"
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

<style scoped>
table th {
    border-color: #dee2e6;
    border-bottom-width: 1px !important;
    text-transform: uppercase;
}
</style>
