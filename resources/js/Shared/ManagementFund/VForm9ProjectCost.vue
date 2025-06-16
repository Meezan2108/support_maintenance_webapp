<script setup>
import { router, useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";
import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import { generateArrYear } from "@/Helpers/date.js";

import VProjectCostSalariedTable from "./Partials/VProjectCostSalariedTable.vue";
import { computed, watch } from "vue";
import _ from "lodash";
import Swal from "sweetalert2";

import { sumCost, formatNumber, getIntValue } from "@/Helpers/number.js";
import { useTaskStore } from "@/Store/task";
import { useNotificationStore } from "@/Store/notification.js";

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

const salariedValue = computed(() => {
    if (_.isEmpty(props.additional.exspenseEstimation)) return [];

    if (_.isEmpty(props.additional.exspenseEstimation.V11000)) return [];

    return props.additional.exspenseEstimation.V11000[0].years;
});

const form = useForm({
    years: years,
    proposal_type: proposalType,
    project_leader_type: identification.value?.project_leader_type,
    cost_salaried: {
        years: salariedValue.value,
    },
    approval_status: 0,
    save_as_draft: 0,
    _method: method,
});

const formStore = useFormStore();

const refProjectCostSeriesDirect = computed(() => {
    return props.additional.refProjectCostSeriesDirect.map((item) => {
        let arrTotalYear = years.value.map((item) => 0);

        if (!props.additional.exspenseEstimation) return { ...item, years: [] };

        let listCost =
            props.additional.exspenseEstimation[item.vseries_code] ?? [];

        for (const cost of listCost) {
            arrTotalYear = arrTotalYear.map(
                (itemYear, index) =>
                    parseInt(itemYear) + parseInt(cost.years[index])
            );
        }

        return {
            ...item,
            years: arrTotalYear,
        };
    });
});

const totalDirect = computed(() => {
    let arrTotalYear = years.value.map((item) => 0);

    for (const cost of refProjectCostSeriesDirect.value) {
        arrTotalYear = arrTotalYear.map(
            (itemYear, index) =>
                getIntValue(itemYear) + getIntValue(cost.years[index])
        );
    }

    return arrTotalYear;
});

const totalProjectCost = computed(() => {
    let isEmpty = _.isEmpty(form.cost_salaried.years);
    return totalDirect.value.map((itemYear, index) => {
        console.log(form.cost_salaried.years[index]);
        return (
            getIntValue(itemYear) +
            (!isEmpty ? getIntValue(form.cost_salaried.years[index] ?? 0) : 0)
        );
    });
});

const emits = defineEmits(["onNext", "onPrev"]);

const handleClickNext = async () => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Submit Proposal!",
    });

    if (!result.isConfirmed) {
        return false;
    }

    form.years = years;
    form.approval_status = 1;
    form.project_leader_type = identification.value?.project_leader_type;
    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            useTaskStore().checkCount();
            useNotificationStore().reloadCount();
            formStore.reset();
        },
    });
};

const handleSaveDraft = async () => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save as draft?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Submit Proposal as Draft!",
    });

    if (!result.isConfirmed) {
        return false;
    }

    form.approval_status = 0;
    form.save_as_draft = 1;
    form.project_leader_type = identification.value?.project_leader_type;
    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            formStore.reset();
        },
    });
};

const handleClickPrev = () => {
    emits("onPrev", form);
};

watch(
    () => form.data(),
    () => {
        formStore.setIsDirty(form.isDirty);
    }
);
</script>
<template>
    <h3>Project Cost</h3>
    <VDevider class="my-3" />

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Salaried Personnel Cost</h6>
            <VProjectCostSalariedTable
                v-model:value="form.cost_salaried"
                :years="years"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Direct Project Expenses</h6>
            <div class="bg-light p-2">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th class="fw-bold">Expenses Category</th>
                                <th
                                    v-for="(year, index) in years"
                                    :key="year"
                                    class="fw-bold"
                                >
                                    <div class="year-count mb-3 text-center">
                                        {{ `YEAR ${index + 1} (RM)` }}
                                    </div>
                                    <div class="year text-center">
                                        {{ year }}
                                    </div>
                                </th>
                                <th class="text-center">Total (RM)</th>
                            </tr>
                            <tr
                                v-for="item in refProjectCostSeriesDirect"
                                :key="item.id"
                            >
                                <td>
                                    {{ item.description }} ({{
                                        item.vseries_code
                                    }})
                                </td>
                                <td
                                    v-for="year in item.years"
                                    :key="year"
                                    class="text-end cost"
                                >
                                    {{ formatNumber(getIntValue(year)) }}
                                </td>
                                <td class="text-end cost">
                                    {{ formatNumber(sumCost(item.years)) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="fw-bold footer">Total Direct</th>
                                <th
                                    v-for="(total, index) in totalDirect"
                                    :key="index + '-total'"
                                    class="fw-bold text-end footer"
                                >
                                    {{ formatNumber(getIntValue(total)) }}
                                </th>
                                <th class="text-end footer">
                                    {{ formatNumber(sumCost(totalDirect)) }}
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Total Project Cost</h6>
            <div class="bg-light p-2">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th
                                    v-for="(year, index) in years"
                                    :key="year"
                                    class="fw-bold"
                                >
                                    <div class="year-count mb-3 text-center">
                                        {{ `YEAR ${index + 1} (RM)` }}
                                    </div>
                                    <div class="year text-center">
                                        {{ year }}
                                    </div>
                                </th>
                                <th class="text-center">Total (RM)</th>
                            </tr>
                            <tr>
                                <td
                                    v-for="(cost, index) in totalProjectCost"
                                    :key="index + 'total-cost'"
                                    class="text-center"
                                >
                                    {{ formatNumber(getIntValue(cost)) }}
                                </td>
                                <td class="text-center">
                                    {{
                                        formatNumber(sumCost(totalProjectCost))
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <VDevider class="mb-4" />
    <div class="text-end">
        <VButton class="me-2" type="button" @onClick="handleClickPrev">
            Back
        </VButton>
        <VButtonSubmit
            class="me-2"
            type="button"
            @onCLickSubmit="handleClickNext"
            :isProcessing="form.processing"
        >
            Submit
        </VButtonSubmit>
        <VButtonSubmit
            class="me-2"
            type="button"
            @onCLickSubmit="handleSaveDraft"
            :isProcessing="form.processing"
        >
            Save as Draft
        </VButtonSubmit>
    </div>
</template>

<style scoped>
table th {
    border-color: #dee2e6;
    border-bottom-width: 1px !important;
    text-transform: uppercase;
}

table th.footer {
    border-bottom-width: 0px !important;
    border-top-width: 1px !important;
}

table td.cost {
    width: 150px;
}
</style>
