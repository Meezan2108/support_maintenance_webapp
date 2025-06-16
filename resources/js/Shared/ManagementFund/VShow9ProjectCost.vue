<script setup>
import { router, useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";

import VProjectCostSalariedTableShow from "./Partials/VProjectCostSalariedTableShow.vue";
import { computed } from "vue";
import _ from "lodash";

import { sumCost, formatNumber } from "@/Helpers/number.js";
import { getIntValue } from "../../Helpers/number";

const props = defineProps({
    additional: Object,
});

const { initValue, method, type: formType, proposalType } = props.additional;

const years = computed(() => {
    let startDate = props.additional.researchApproach?.schedule_start_date;
    let duration = props.additional.researchApproach?.schedule_duration;
    if (!startDate || !duration) {
        return [];
    }

    let d = new Date(startDate + "-01");
    let startYear = d.getFullYear();
    d.setMonth(d.getMonth() + parseInt(duration));

    let endYear = d.getFullYear();

    let years = [];

    for (let i = startYear; i <= endYear; i++) {
        years.push(i);
    }

    return years;
});

const salariedValue = computed(() => {
    if (_.isEmpty(props.additional.exspenseEstimation)) return [];

    if (_.isEmpty(props.additional.exspenseEstimation.V11000)) return [];

    return props.additional.exspenseEstimation.V11000[0].years;
});

const form = useForm({
    years: years,
    proposal_type: proposalType,
    cost_salaried: {
        years: salariedValue.value,
    },
    approval_status: 0,
});

const refProjectCostSeriesDirect = computed(() => {
    return props.additional.refProjectCostSeriesDirect.map((item) => {
        let arrTotalYear = years.value.map((item) => 0);

        if (!props.additional.exspenseEstimation) return { ...item, years: [] };

        let listCost =
            props.additional.exspenseEstimation[item.vseries_code] ?? [];

        for (const cost of listCost) {
            arrTotalYear = arrTotalYear.map(
                (itemYear, index) =>
                    getIntValue(itemYear) + getIntValue(cost.years[index])
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
    return totalDirect.value.map(
        (itemYear, index) =>
            getIntValue(itemYear) +
            (!isEmpty ? getIntValue(form.cost_salaried.years[index]) : 0)
    );
});
</script>
<template>
    <h3>Project Cost</h3>
    <VDevider class="my-3" />

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Salaried Personnel Cost</h6>
            <VProjectCostSalariedTableShow
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
                                    {{ formatNumber(year) }}
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
                                    {{ formatNumber(total) }}
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
